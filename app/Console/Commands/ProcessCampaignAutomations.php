<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignAutomationBatchJob;
use App\Models\CampaignAutomation;
use App\Models\CampaignAutomationLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Models\Role;

class ProcessCampaignAutomations extends Command
{
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

        // Batch size is capped by daily_send_cap below. Actual sending happens in
        // a queued job (SendCampaignAutomationBatchJob) — this command never
        // blocks on SMTP, which matters at 66k+ recipient scale: a synchronous
        // send loop here would tie up a PHP-FPM worker (and the shared-hosting
        // CPU/IO quota) for the whole batch and starve every other request.
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

        // Exclusions run as correlated NOT EXISTS subqueries rather than pulling
        // every excluded email into a PHP array and building a whereNotIn(...)
        // list — with 66k+ candidate users and a growing log table, that array
        // approach gets slower (and more memory-hungry) every day.
        $recipients = User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->where('role_id', $role->id)
            ->whereNotNull('email')
            ->whereNotExists(function ($query) use ($auto) {
                // Never retry an address that previously bounced/failed for this automation.
                $query->select(DB::raw(1))
                    ->from('campaign_automation_logs')
                    ->whereColumn('campaign_automation_logs.email', 'users.email')
                    ->where('campaign_automation_logs.automation_id', $auto->id)
                    ->where('campaign_automation_logs.status', 'failed');
            })
            ->when($auto->resend_gap_days > 0, function ($query) use ($auto) {
                // Resend gap cooldown, global across ALL campaigns/automations:
                // skip anyone who got ANY campaign email in the last N days, so
                // e.g. Ayub's campaign and Ali Hassan's campaign don't both hit
                // the same client back-to-back.
                $query->whereNotExists(function ($sub) use ($auto) {
                    $sub->select(DB::raw(1))
                        ->from('campaign_automation_logs')
                        ->whereColumn('campaign_automation_logs.email', 'users.email')
                        ->where('campaign_automation_logs.status', 'sent')
                        ->where('campaign_automation_logs.sent_at', '>=', now()->subDays($auto->resend_gap_days));
                });
            })
            ->select(['id', 'email', 'name'])
            ->limit($sendThisRun)
            ->get();

        Log::info("[Automations] #{$auto->id} \"{$auto->name}\": role={$auto->target_role}, recipients_found={$recipients->count()}, send_limit={$sendThisRun}");

        if ($recipients->isEmpty()) {
            Log::info("[Automations] #{$auto->id} \"{$auto->name}\": no eligible recipients — all sent or skipped");
            return;
        }

        $updates = [
            'last_run_at'          => now(),
            'emails_sent_in_batch' => 0, // batches always complete within a single run now
            'next_run_at'          => $this->calculateNextRun($auto),
        ];

        if ($auto->frequency === 'once') {
            $updates['status']      = 'completed';
            $updates['next_run_at'] = null;
        }

        // Check end_date against calculated next run
        if (!empty($updates['next_run_at']) && $auto->end_date && $updates['next_run_at']->copy()->startOfDay()->gt($auto->end_date)) {
            $updates['status']      = 'completed';
            $updates['next_run_at'] = null;
        }

        // Persist scheduling changes BEFORE dispatching — the job checks the
        // automation's status when it runs (which, on QUEUE_CONNECTION=sync,
        // is immediately) to decide whether to send the completion email.
        $auto->update($updates);

        $nextInfo   = !empty($updates['next_run_at']) ? $updates['next_run_at']->toDateTimeString() : 'none';
        $statusInfo = $updates['status'] ?? $auto->status;
        Log::info("[Automations] #{$auto->id} \"{$auto->name}\": scheduled — status={$statusInfo}, next_run={$nextInfo}");

        SendCampaignAutomationBatchJob::dispatch(
            $auto->id,
            $recipients->map(fn ($u) => ['id' => $u->id, 'email' => $u->email, 'name' => $u->name])->all(),
            (int) $auto->email_delay_seconds
        );

        $this->info("Automation #{$auto->id} \"{$auto->name}\": queued {$recipients->count()} email(s) for background sending.");
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
