<?php

namespace App\Console\Commands;

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
        $specificId = $this->option('id');

        $query = CampaignAutomation::with('campaign')->where('status', 'active');

        if ($specificId) {
            $query->where('id', $specificId);
        } else {
            $query->where('next_run_at', '<=', now());
        }

        $due = $query->get();

        if ($due->isEmpty()) {
            return 0;
        }

        foreach ($due as $auto) {
            $this->processAutomation($auto);
        }

        return 0;
    }

    private function processAutomation(CampaignAutomation $auto): void
    {
        $campaign = $auto->campaign;
        if (!$campaign) {
            $this->warn("Automation #{$auto->id}: campaign missing, cancelling.");
            $auto->update(['status' => 'cancelled']);
            return;
        }

        if ($auto->end_date && now()->startOfDay()->gt($auto->end_date)) {
            $auto->update(['status' => 'completed']);
            $this->info("Automation #{$auto->id} past end_date — marked completed.");
            return;
        }

        $role = Role::where('name', $auto->target_role)->first();
        if (!$role) {
            $this->warn("Automation #{$auto->id}: role '{$auto->target_role}' not found.");
            return;
        }

        $delay = (int) $auto->email_delay_seconds;

        // With delay: send 1 email per cron tick. Without delay: send full batch at once.
        $sendThisRun = $delay > 0 ? 1 : $auto->batch_size;

        $skipEmails = $this->getSkipEmails($auto);

        $recipients = User::withoutGlobalScope(User::SCOPE_EXCLUDE_HOMEY)
            ->where('role_id', $role->id)
            ->whereNotNull('email')
            ->whereNotIn('email', $skipEmails)
            ->select(['id', 'email', 'name'])
            ->limit($sendThisRun)
            ->get();

        $sent   = 0;
        $failed = 0;
        $smtp   = $campaign->smtpAccount;
        $mailer = $this->getMailer($smtp);

        foreach ($recipients as $user) {
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
                Log::error("Automation #{$auto->id} send failed to {$user->email}: " . $e->getMessage());
            }
        }

        $updates = [
            'last_run_at'       => now(),
            'emails_sent_total' => $auto->emails_sent_total + $sent,
        ];

        if ($delay > 0) {
            $sentInBatch   = $auto->emails_sent_in_batch + $sent;
            $noMoreRecipients = $recipients->count() < $sendThisRun;
            $batchComplete = $sentInBatch >= $auto->batch_size || $noMoreRecipients;

            if ($batchComplete) {
                // Batch done — schedule the next full run
                $updates['emails_sent_in_batch'] = 0;
                $updates['next_run_at']          = $this->calculateNextRun($auto);

                if ($auto->frequency === 'once') {
                    $updates['status']      = 'completed';
                    $updates['next_run_at'] = null;
                }

                $this->info("Automation #{$auto->id} \"{$auto->name}\": batch complete — sent={$sent}, failed={$failed}");
            } else {
                // More emails remain in this batch — wait delay, then continue
                $updates['emails_sent_in_batch'] = $sentInBatch;
                $updates['next_run_at']          = now()->addSeconds($delay);

                $remaining = $auto->batch_size - $sentInBatch;
                $this->info("Automation #{$auto->id} \"{$auto->name}\": sent={$sent} (batch {$sentInBatch}/{$auto->batch_size}), next email in {$delay}s, {$remaining} remaining");
            }
        } else {
            // No delay — entire batch sent in one run
            $updates['emails_sent_in_batch'] = 0;
            $updates['next_run_at']          = $this->calculateNextRun($auto);

            if ($auto->frequency === 'once') {
                $updates['status']      = 'completed';
                $updates['next_run_at'] = null;
            }

            $this->info("Automation #{$auto->id} \"{$auto->name}\": sent={$sent}, failed={$failed}, next=" . ($updates['next_run_at'] ? $updates['next_run_at']->toDateTimeString() : 'none'));
        }

        // Check end_date against calculated next run
        if (!empty($updates['next_run_at']) && $auto->end_date && $updates['next_run_at']->startOfDay()->gt($auto->end_date)) {
            $updates['status']      = 'completed';
            $updates['next_run_at'] = null;
        }

        $auto->update($updates);
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
