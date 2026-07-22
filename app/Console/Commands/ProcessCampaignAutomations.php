<?php

namespace App\Console\Commands;

use App\Mail\AutomationCompletedMail;
use App\Mail\MarketingCampaignMail;
use App\Models\CampaignAutomation;
use App\Models\CampaignAutomationLog;
use App\Models\User;
use App\Utils\Traits\CampaignMailerTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use TCG\Voyager\Models\Role;

class ProcessCampaignAutomations extends Command
{
    use CampaignMailerTrait;
    protected $signature   = 'automations:process {--id= : Run a specific automation regardless of next_run_at}';
    protected $description = 'Send scheduled campaign automation batches';

    public function handle(): int
    {
        set_time_limit(0);

        $specificId = $this->option('id');

        $query = CampaignAutomation::with('campaign')->where('status', 'active');

        if ($specificId) {
            $query->where('id', $specificId);
        } else {
            $query->where('next_run_at', '<=', now());
        }

        $due = $query->get();

        if ($due->isEmpty()) {
            Log::info('[Automations] Scheduler ran — no automations due at ' . now()->toDateTimeString());
            return 0;
        }

        Log::info('[Automations] Scheduler ran — ' . $due->count() . ' automation(s) due: [' . $due->pluck('id')->join(', ') . ']');

        foreach ($due as $auto) {
            $this->processAutomation($auto);
        }

        return 0;
    }

    private function processAutomation(CampaignAutomation $auto): void
    {
        Log::info("[Automations] #{$auto->id} \"{$auto->name}\" — processing");

        $campaign = $auto->campaign;
        if (!$campaign) {
            Log::error("[Automations] #{$auto->id}: campaign missing — cancelling automation");
            $this->warn("Automation #{$auto->id}: campaign missing, cancelling.");
            $auto->update(['status' => 'cancelled']);
            return;
        }

        if ($auto->end_date && now()->startOfDay()->gt($auto->end_date)) {
            Log::info("[Automations] #{$auto->id} \"{$auto->name}\": past end_date — marked completed");
            $auto->update(['status' => 'completed']);
            $this->info("Automation #{$auto->id} past end_date — marked completed.");
            return;
        }

        // Skip weekends — reschedule to next Monday at the same send_time
        if ($auto->skip_weekends && now()->isWeekend()) {
            $nextMonday = now()->next('Monday')->setTimeFromTimeString($auto->send_time);
            $auto->update(['next_run_at' => $nextMonday]);
            Log::info("[Automations] #{$auto->id} \"{$auto->name}\": weekend skip — rescheduled to {$nextMonday->toDateTimeString()}");
            $this->info("Automation #{$auto->id}: weekend — rescheduled to {$nextMonday->toDateTimeString()}");
            return;
        }

        $role = Role::where('name', $auto->target_role)->first();
        if (!$role) {
            Log::error("[Automations] #{$auto->id} \"{$auto->name}\": role '{$auto->target_role}' not found in DB");
            $this->warn("Automation #{$auto->id}: role '{$auto->target_role}' not found.");
            return;
        }

        $delay = (int) $auto->email_delay_seconds;

        // Always attempt the full batch in a single cron run. email_delay_seconds is
        // honored as an in-process pause between sends below, not by spreading the
        // batch across cron ticks — the real cron here only invokes the scheduler
        // ~once/day, so waiting for a "next tick" silently stalled batches and
        // desynced next_run_at from send_time.
        $sendThisRun = $auto->batch_size;

        // Daily send capacity — hard cap per calendar day for this automation
        if ($auto->daily_send_cap > 0) {
            $sentToday = CampaignAutomationLog::where('automation_id', $auto->id)
                ->where('status', 'sent')
                ->whereDate('sent_at', today())
                ->count();

            if ($sentToday >= $auto->daily_send_cap) {
                Log::info("[Automations] #{$auto->id} \"{$auto->name}\": daily cap {$auto->daily_send_cap} reached (sent today: {$sentToday}) — skipping until tomorrow");
                $this->info("Automation #{$auto->id} \"{$auto->name}\": daily cap of {$auto->daily_send_cap} reached — skipping until tomorrow.");
                return;
            }

            $sendThisRun = min($sendThisRun, $auto->daily_send_cap - $sentToday);
            Log::info("[Automations] #{$auto->id}: daily cap {$auto->daily_send_cap}, sent today {$sentToday}, allowed this run: {$sendThisRun}");
        }

        $skipEmails = $this->getSkipEmails($auto);

        $recipients = User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->where('role_id', $role->id)
            ->whereNotNull('email')
            ->whereNotIn('email', $skipEmails)
            ->select(['id', 'email', 'name'])
            ->limit($sendThisRun)
            ->get();

        Log::info("[Automations] #{$auto->id} \"{$auto->name}\": role={$auto->target_role}, skip_count=" . count($skipEmails) . ", recipients_found={$recipients->count()}, send_limit={$sendThisRun}");

        if ($recipients->isEmpty()) {
            Log::info("[Automations] #{$auto->id} \"{$auto->name}\": no eligible recipients — all sent or skipped");
            return;
        }

        $sent   = 0;
        $failed = 0;
        $smtp   = $campaign->smtpAccount;
        $mailer = $this->getMailer($smtp);

        $recipientCount = $recipients->count();

        foreach ($recipients as $index => $user) {
            try {
                $mailer->to($user->email, $user->name ?? '')
                    ->send(new MarketingCampaignMail($campaign, $user->name ?? '', $smtp));

                CampaignAutomationLog::create([
                    'automation_id' => $auto->id,
                    'campaign_id'   => $campaign->id,
                    'user_id'       => $user->id,
                    'email'         => $user->email,
                    'name'          => $user->name ?? '',
                    'status'        => 'sent',
                    'sent_at'       => now(),
                ]);
                Log::info("[Automations] #{$auto->id}: sent → {$user->email}");
                $sent++;
            } catch (\Throwable $e) {
                CampaignAutomationLog::create([
                    'automation_id' => $auto->id,
                    'campaign_id'   => $campaign->id,
                    'user_id'       => $user->id,
                    'email'         => $user->email,
                    'name'          => $user->name ?? '',
                    'status'        => 'failed',
                    'error'         => $e->getMessage(),
                    'sent_at'       => now(),
                ]);
                $failed++;
                Log::error("[Automations] #{$auto->id}: FAILED → {$user->email} — " . $e->getMessage());
            }

            // Pace sends to avoid spam-filter throttling. Skip the wait after the
            // last recipient — no reason to hold the process open once done.
            if ($delay > 0 && $index < $recipientCount - 1) {
                sleep($delay);
            }
        }

        $updates = [
            'last_run_at'          => now(),
            'emails_sent_total'    => $auto->emails_sent_total + $sent,
            'emails_sent_in_batch' => 0, // batches always complete within a single run now
            'next_run_at'          => $this->calculateNextRun($auto),
        ];

        if ($auto->frequency === 'once') {
            $updates['status']      = 'completed';
            $updates['next_run_at'] = null;
        }

        $this->info("Automation #{$auto->id} \"{$auto->name}\": sent={$sent}, failed={$failed}, next=" . ($updates['next_run_at'] ? $updates['next_run_at']->toDateTimeString() : 'none'));

        // Check end_date against calculated next run
        if (!empty($updates['next_run_at']) && $auto->end_date && $updates['next_run_at']->copy()->startOfDay()->gt($auto->end_date)) {
            $updates['status']      = 'completed';
            $updates['next_run_at'] = null;
        }

        $auto->update($updates);

        $nextInfo = !empty($updates['next_run_at']) ? $updates['next_run_at']->toDateTimeString() : 'none';
        $statusInfo = $updates['status'] ?? $auto->status;
        Log::info("[Automations] #{$auto->id} \"{$auto->name}\": done — sent={$sent}, failed={$failed}, status={$statusInfo}, next_run={$nextInfo}");

        // Notify on completion
        if (!empty($updates['status']) && $updates['status'] === 'completed' && $auto->notify_email) {
            Mail::to($auto->notify_email)
                ->send(new AutomationCompletedMail($auto, $auto->emails_sent_total + $sent, $failed));
        }
    }

    private function getSkipEmails(CampaignAutomation $auto): array
    {
        // 1. Always skip bounced/failed addresses from this automation — never retry them
        $failed = CampaignAutomationLog::where('automation_id', $auto->id)
            ->where('status', 'failed')
            ->pluck('email')
            ->all();

        // 2. Resend gap cooldown (global across ALL campaigns):
        //    If resend_gap_days = 0  → no cooldown, send to everyone every run
        //    If resend_gap_days = 30 → skip anyone who got ANY campaign email in the last 30 days
        //    This prevents Ayub's campaign and Ali Hassan's campaign hitting the same client
        $sent = [];
        if ($auto->resend_gap_days > 0) {
            $sent = CampaignAutomationLog::where('status', 'sent')
                ->where('sent_at', '>=', now()->subDays($auto->resend_gap_days))
                ->pluck('email')
                ->all();
        }

        return array_unique(array_merge($failed, $sent));
    }

    private function calculateNextRun(CampaignAutomation $auto): ?Carbon
    {
        $base = $auto->next_run_at ?? now();

        return match ($auto->frequency) {
            'once'    => null,
            'daily'   => $base->copy()->addDay(),
            'weekly'  => $base->copy()->addWeek(),
            'monthly' => $base->copy()->addMonthNoOverflow(),
            default   => null,
        };
    }
}
