<?php

namespace App\Http\Controllers\Voyager;

use App\Jobs\SendCampaignBatchJob;
use App\Mail\MarketingCampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignLog;
use App\Models\SmtpAccount;
use App\Models\User;
use App\Utils\Traits\CampaignMailerTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use TCG\Voyager\Models\Role;

class EmailCampaignController extends Controller
{
    use CampaignMailerTrait;
    public function __construct()
    {
        $this->middleware(['campaign.access']);
    }

    // ── List ────────────────────────────────────────────────────────────────
    public function index()
    {
        $campaigns = EmailCampaign::latest()->paginate(20);
        return view('vendor.voyager.email-campaigns.index', compact('campaigns'));
    }

    // ── Create ──────────────────────────────────────────────────────────────
    public function create()
    {
        $roles        = Role::orderBy('display_name')->get();
        $smtpAccounts = SmtpAccount::where('is_active', true)->orderBy('name')->get();
        return view('vendor.voyager.email-campaigns.edit-add', compact('roles', 'smtpAccounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'subject'          => 'required|string|max:255',
            'html_body'        => 'required|string',
            'text_body'        => 'nullable|string',
            'from_name'        => 'required|string|max:255',
            'from_email'       => 'required|email',
            'target_role'      => 'required|string',
            'smtp_account_id'  => 'nullable|exists:smtp_accounts,id',
        ]);

        $data['text_body']  = $data['text_body'] ?? strip_tags($data['html_body']);
        $data['created_by'] = auth()->id();

        $campaign = EmailCampaign::create($data);

        return redirect()->route('email-campaigns.index')
            ->with('success', "Campaign \"{$campaign->name}\" created.");
    }

    // ── Edit ────────────────────────────────────────────────────────────────
    public function edit(int $id)
    {
        $campaign     = EmailCampaign::findOrFail($id);
        $roles        = Role::orderBy('display_name')->get();
        $smtpAccounts = SmtpAccount::where('is_active', true)->orderBy('name')->get();
        return view('vendor.voyager.email-campaigns.edit-add', compact('campaign', 'roles', 'smtpAccounts'));
    }

    public function update(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        if ($campaign->isSent()) {
            return back()->with('error', 'Cannot edit a sent campaign.');
        }

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'html_body'       => 'required|string',
            'text_body'       => 'nullable|string',
            'from_name'       => 'required|string|max:255',
            'from_email'      => 'required|email',
            'target_role'     => 'required|string',
            'smtp_account_id' => 'nullable|exists:smtp_accounts,id',
        ]);

        $campaign->update($data);

        return redirect()->route('email-campaigns.show', $campaign->id)
            ->with('success', 'Campaign updated.');
    }

    // ── Show / Stats ─────────────────────────────────────────────────────────
    public function show(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $role     = Role::where('name', $campaign->target_role)->first();
        $total    = $role ? User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)->where('role_id', $role->id)->count() : 0;

        $stats = [
            'total'   => $total,
            'sent'    => $campaign->sent_count,
            'failed'  => $campaign->failed_count,
            'pending' => max(0, $total - $campaign->sent_count - $campaign->failed_count),
        ];

        $logs = EmailCampaignLog::where('campaign_id', $id)->latest()->paginate(50);

        return view('vendor.voyager.email-campaigns.show', compact('campaign', 'stats', 'logs'));
    }

    // ── Preview ──────────────────────────────────────────────────────────────
    public function preview(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $tempName = 'John Doe';

        $mail = new MarketingCampaignMail($campaign, $tempName);
        $rendered = $mail->render();

        return response($rendered)->header('Content-Type', 'text/html');
    }

    // ── Send Test Email ───────────────────────────────────────────────────────
    public function sendTest(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        $request->validate(['test_email' => 'required|email']);
        $testEmail = $request->input('test_email');
        $testName  = $request->input('test_name', 'Test User');

        try {
            $smtp = $campaign->smtpAccount;
            $this->getMailer($smtp)
                ->to($testEmail, $testName)
                ->send(new MarketingCampaignMail($campaign, $testName, $smtp));

            return back()->with('success', "Test email sent to {$testEmail}");
        } catch (\Throwable $e) {
            Log::error('Campaign test email failed', [
                'campaign_id' => $id,
                'to'          => $testEmail,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Send failed: ' . $e->getMessage());
        }
    }

    // ── Send to One Specific Email ────────────────────────────────────────────
    public function sendSingle(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        $request->validate([
            'to_email' => 'required|email',
            'to_name'  => 'nullable|string|max:255',
        ]);

        $toEmail = $request->input('to_email');
        $toName  = $request->input('to_name', '');

        try {
            $smtp = $campaign->smtpAccount;
            $this->getMailer($smtp)
                ->to($toEmail, $toName)
                ->send(new MarketingCampaignMail($campaign, $toName, $smtp));

            EmailCampaignLog::updateOrCreate(
                ['campaign_id' => $id, 'email' => $toEmail],
                ['name' => $toName, 'status' => 'sent', 'sent_at' => now(), 'error' => null]
            );

            $campaign->increment('sent_count');

            return back()->with('success', "Email sent to {$toEmail}");
        } catch (\Throwable $e) {
            Log::error('Campaign single send failed', [
                'campaign_id' => $id,
                'to'          => $toEmail,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Send failed: ' . $e->getMessage());
        }
    }

    // ── Bulk Send (synchronous — no queue required) ───────────────────────────
    public function dispatch(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        if ($campaign->isSent()) {
            return back()->with('error', 'Campaign already sent.');
        }

        $role = Role::where('name', $campaign->target_role)->first();
        if (!$role) {
            return back()->with('error', "Role '{$campaign->target_role}' not found.");
        }

        $batchSize = (int) (setting('marketing.batch_size') ?? 200);

        $sentEmails = EmailCampaignLog::where('campaign_id', $id)
            ->where('status', 'sent')
            ->pluck('email')
            ->flip()
            ->all();

        $dispatched = 0;

        User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->where('role_id', $role->id)
            ->whereNotNull('email')
            ->select(['email', 'name'])
            ->chunk($batchSize, function ($users) use ($campaign, $sentEmails, &$dispatched) {
                $batch = $users
                    ->filter(fn($u) => !isset($sentEmails[$u->email]))
                    ->map(fn($u) => ['email' => $u->email, 'name' => $u->name ?? ''])
                    ->values()
                    ->all();

                if (count($batch)) {
                    // dispatchSync runs the job immediately without a queue worker
                    SendCampaignBatchJob::dispatchSync($campaign->id, $batch);
                    $dispatched += count($batch);
                }
            });

        $campaign->update(['status' => 'sending']);

        return back()->with('success', "Sent {$dispatched} emails directly (no queue).");
    }

    // ── Search eligible recipients (AJAX, per-campaign) ──────────────────────
    public function searchRecipients(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $role     = Role::where('name', $campaign->target_role)->first();

        if (!$role) {
            return response()->json(['results' => []]);
        }

        $q = trim($request->input('q', ''));

        $users = User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->where('users.role_id', $role->id)
            ->whereNotNull('users.email')
            ->whereNotExists(function ($sub) use ($id) {
                $sub->from('email_campaign_logs')
                    ->whereColumn('email_campaign_logs.email', 'users.email')
                    ->where('email_campaign_logs.campaign_id', $id)
                    ->where('email_campaign_logs.status', 'sent');
            })
            ->when($q, fn($query) => $query->where(function ($w) use ($q) {
                $w->where('users.email', 'like', "%{$q}%")
                  ->orWhere('users.name', 'like', "%{$q}%");
            }))
            ->select('users.id', 'users.name', 'users.email')
            ->limit(60)
            ->get();

        return response()->json([
            'results' => $users->map(fn($u) => [
                'id'    => $u->id,
                'text'  => ($u->name ? "{$u->name} " : '') . "<{$u->email}>",
                'email' => $u->email,
                'name'  => $u->name ?? '',
            ]),
        ]);
    }

    // ── Send to hand-picked users ─────────────────────────────────────────────
    public function sendToSelected(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        $request->validate(['user_ids' => 'required|array|min:1', 'user_ids.*' => 'integer']);

        $users = User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->whereIn('id', $request->input('user_ids'))
            ->whereNotNull('email')
            ->get();

        $sent    = 0;
        $failed  = 0;
        $skipped = 0;
        $smtp    = $campaign->smtpAccount;
        $mailer  = $this->getMailer($smtp);

        foreach ($users as $user) {
            $alreadySent = EmailCampaignLog::where('campaign_id', $id)
                ->where('email', $user->email)
                ->where('status', 'sent')
                ->exists();

            if ($alreadySent) {
                $skipped++;
                continue;
            }

            try {
                $mailer->to($user->email, $user->name ?? '')
                    ->send(new MarketingCampaignMail($campaign, $user->name ?? '', $smtp));

                EmailCampaignLog::updateOrCreate(
                    ['campaign_id' => $id, 'email' => $user->email],
                    ['name' => $user->name, 'status' => 'sent', 'sent_at' => now(), 'error' => null]
                );
                $campaign->increment('sent_count');
                $sent++;
            } catch (\Throwable $e) {
                EmailCampaignLog::updateOrCreate(
                    ['campaign_id' => $id, 'email' => $user->email],
                    ['name' => $user->name, 'status' => 'failed', 'sent_at' => null, 'error' => $e->getMessage()]
                );
                $campaign->increment('failed_count');
                $failed++;
            }
        }

        $msg = "Sent: {$sent}";
        if ($skipped) $msg .= ", Already sent (skipped): {$skipped}";
        if ($failed)  $msg .= ", Failed: {$failed}";

        return $failed
            ? back()->with('error', $msg)
            : back()->with('success', $msg);
    }

    // ── Mark Complete ─────────────────────────────────────────────────────────
    public function markComplete(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->update(['status' => 'sent', 'sent_at' => $campaign->sent_at ?? now()]);
        return back()->with('success', 'Campaign marked as sent.');
    }

    // ── Configure marketing SMTP before Mail::to() ───────────────────────────
    private function configureMailer(): void
    {
        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => setting('marketing.smtp_host')       ?: 'smtp.titan.email',
            'port'       => (int) (setting('marketing.smtp_port') ?: 465),
            'encryption' => setting('marketing.smtp_encryption') ?: 'ssl',
            'username'   => setting('marketing.smtp_username')   ?: '',
            'password'   => setting('marketing.smtp_password')   ?: '',
            'timeout'    => null,
            'auth_mode'  => null,
        ]);
        app('mail.manager')->purge('smtp');
    }

    // ── Delete ────────────────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->logs()->delete();
        $campaign->delete();
        return redirect()->route('email-campaigns.index')->with('success', 'Campaign deleted.');
    }
}
