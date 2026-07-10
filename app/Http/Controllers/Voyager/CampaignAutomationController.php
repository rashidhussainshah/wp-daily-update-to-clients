<?php

namespace App\Http\Controllers\Voyager;

use App\Models\CampaignAutomation;
use App\Models\CampaignAutomationLog;
use App\Models\EmailCampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use TCG\Voyager\Models\Role;

class CampaignAutomationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['campaign.access']);
    }

    public function index()
    {
        $automations = CampaignAutomation::with('campaign')
            ->latest()
            ->paginate(20);

        return view('vendor.voyager.campaign-automations.index', compact('automations'));
    }

    public function create()
    {
        $campaigns = EmailCampaign::orderBy('name')->get();
        $roles     = Role::orderBy('display_name')->get();

        return view('vendor.voyager.campaign-automations.edit-add', compact('campaigns', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = auth()->id();
        $data['next_run_at'] = $this->buildFirstRun($data);
        $data['status']     = 'active';

        $auto = CampaignAutomation::create($data);

        return redirect()->route('campaign-automations.index')
            ->with('success', "Automation \"{$auto->name}\" created and scheduled.");
    }

    public function edit(int $id)
    {
        $automation = CampaignAutomation::findOrFail($id);
        $campaigns  = EmailCampaign::orderBy('name')->get();
        $roles      = Role::orderBy('display_name')->get();

        return view('vendor.voyager.campaign-automations.edit-add', compact('automation', 'campaigns', 'roles'));
    }

    public function update(Request $request, int $id)
    {
        $automation = CampaignAutomation::findOrFail($id);
        $data = $this->validated($request);

        // Recalculate next_run_at only if schedule fields changed
        if ($this->scheduleChanged($automation, $data)) {
            $data['next_run_at'] = $this->buildFirstRun($data);
        }

        $automation->update($data);

        return redirect()->route('campaign-automations.index')
            ->with('success', 'Automation updated.');
    }

    public function show(int $id)
    {
        $automation = CampaignAutomation::with('campaign')->findOrFail($id);
        $logs = CampaignAutomationLog::where('automation_id', $id)
            ->orderByDesc('sent_at')
            ->paginate(50);

        $stats = [
            'sent'   => CampaignAutomationLog::where('automation_id', $id)->where('status', 'sent')->count(),
            'failed' => CampaignAutomationLog::where('automation_id', $id)->where('status', 'failed')->count(),
        ];

        return view('vendor.voyager.campaign-automations.show', compact('automation', 'logs', 'stats'));
    }

    public function pause(int $id)
    {
        CampaignAutomation::findOrFail($id)->update(['status' => 'paused']);
        return back()->with('success', 'Automation paused.');
    }

    public function resume(int $id)
    {
        $auto = CampaignAutomation::findOrFail($id);

        // If next_run_at is in the past (it was paused for a while), push it to now so it runs on next cron tick
        $nextRun = $auto->next_run_at;
        if (!$nextRun || $nextRun->isPast()) {
            $nextRun = now()->addMinute();
        }

        $auto->update(['status' => 'active', 'next_run_at' => $nextRun]);
        return back()->with('success', 'Automation resumed.');
    }

    public function runNow(int $id)
    {
        $auto = CampaignAutomation::findOrFail($id);

        if (!in_array($auto->status, ['active', 'paused'])) {
            return back()->with('error', "Cannot run — automation is {$auto->status}.");
        }

        // Temporarily force active so the command will process it
        $waspaused = $auto->status === 'paused';
        if ($waspaused) {
            $auto->update(['status' => 'active']);
        }

        Artisan::call('automations:process', ['--id' => $id]);

        $output = trim(Artisan::output());

        // Restore paused state if it was paused (command may have changed status)
        // Only restore if command didn't mark it completed/cancelled
        $auto->refresh();
        if ($waspaused && $auto->status === 'active') {
            $auto->update(['status' => 'paused']);
        }

        $message = $output ?: "Automation #{$id} triggered successfully.";

        return redirect()->route('campaign-automations.show', $id)
            ->with('success', $message);
    }

    public function cancel(int $id)
    {
        CampaignAutomation::findOrFail($id)->update(['status' => 'cancelled', 'next_run_at' => null]);
        return back()->with('success', 'Automation cancelled.');
    }

    public function destroy(int $id)
    {
        $auto = CampaignAutomation::findOrFail($id);
        $auto->logs()->delete();
        $auto->delete();
        return redirect()->route('campaign-automations.index')->with('success', 'Automation deleted.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'               => 'required|string|max:255',
            'campaign_id'        => 'required|exists:email_campaigns,id',
            'frequency'          => 'required|in:once,daily,weekly,monthly',
            'send_time'          => 'required|date_format:H:i',
            'send_day_of_week'   => 'nullable|integer|min:0|max:6',
            'send_day_of_month'  => 'nullable|integer|min:1|max:31',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after:start_date',
            'batch_size'           => 'required|integer|min:1|max:500',
            'resend_gap_days'      => 'required|integer|min:0',
            'email_delay_seconds'  => 'required|integer|min:0',
            'target_role'          => 'required|string',
            'skip_weekends'        => 'nullable|boolean',
            'daily_send_cap'       => 'nullable|integer|min:1|max:9999',
            'notify_email'         => 'nullable|email|max:150',
        ]) + ['skip_weekends' => $request->boolean('skip_weekends')];
    }

    private function buildFirstRun(array $data): Carbon
    {
        [$h, $m] = explode(':', $data['send_time']);

        $base = Carbon::parse($data['start_date'])->setTime((int)$h, (int)$m, 0);

        // If start_date is today but time has passed, push to next occurrence
        if ($base->isPast()) {
            $base = match ($data['frequency']) {
                'once'    => $base, // run as soon as possible
                'daily'   => $base->addDay(),
                'weekly'  => $base->addWeek(),
                'monthly' => $base->addMonthNoOverflow(),
                default   => $base,
            };
        }

        return $base;
    }

    private function scheduleChanged(CampaignAutomation $auto, array $data): bool
    {
        return $auto->frequency    !== $data['frequency']
            || $auto->send_time    !== $data['send_time'] . ':00'
            || $auto->start_date->toDateString() !== $data['start_date'];
    }
}
