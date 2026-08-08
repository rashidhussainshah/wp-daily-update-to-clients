<?php

namespace App\Jobs;

use App\Mail\AutomationCompletedMail;
use App\Mail\MarketingCampaignMail;
use App\Models\CampaignAutomation;
use App\Models\CampaignAutomationLog;
use App\Utils\Traits\CampaignMailerTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignAutomationBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CampaignMailerTrait;

    public int $tries = 3;
    public int $timeout;

    public function __construct(
        public int $automationId,
        public array $recipients,  // [['id'=>1,'email'=>'...','name'=>'...']]
        public int $delaySeconds = 0
    ) {
        // Must cover the longest this batch can legitimately take — spacing
        // emails out across a whole day means the job runs for hours, not
        // the Laravel default hour. +30min buffer for SMTP/connection overhead.
        $this->timeout = max(3600, count($recipients) * $delaySeconds + 1800);
    }

    public function handle(): void
    {
        $auto = CampaignAutomation::with('campaign')->find($this->automationId);
        if (!$auto || !$auto->campaign) {
            $auto?->update(['queued_at' => null]);
            return;
        }

        $campaign = $auto->campaign;
        $smtp     = $campaign->smtpAccount;
        $mailer   = $this->getMailer($smtp);
        $delay    = $this->delaySeconds;
        $count    = count($this->recipients);

        $sent   = 0;
        $failed = 0;

        foreach ($this->recipients as $index => $recipient) {
            $email = $recipient['email'];
            $name  = $recipient['name'] ?? '';

            try {
                // Defense against the same batch being dispatched twice (e.g. a
                // double click on "Run Now" before the first job finishes).
                $alreadySent = CampaignAutomationLog::where('automation_id', $auto->id)
                    ->where('email', $email)
                    ->where('status', 'sent')
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                $mailer->to($email, $name)
                    ->send(new MarketingCampaignMail($campaign, $name, $smtp));

                CampaignAutomationLog::create([
                    'automation_id' => $auto->id,
                    'campaign_id'   => $campaign->id,
                    'user_id'       => $recipient['id'] ?? null,
                    'email'         => $email,
                    'name'          => $name,
                    'status'        => 'sent',
                    'sent_at'       => now(),
                ]);
                Log::info("[Automations] #{$auto->id}: sent → {$email}");
                $sent++;
            } catch (\Throwable $e) {
                CampaignAutomationLog::create([
                    'automation_id' => $auto->id,
                    'campaign_id'   => $campaign->id,
                    'user_id'       => $recipient['id'] ?? null,
                    'email'         => $email,
                    'name'          => $name,
                    'status'        => 'failed',
                    'error'         => $e->getMessage(),
                    'sent_at'       => now(),
                ]);
                $failed++;
                Log::error("[Automations] #{$auto->id}: FAILED → {$email} — " . $e->getMessage());
            }

            if ($delay > 0 && $index < $count - 1) {
                sleep($delay);
            }
        }

        $auto->increment('emails_sent_total', $sent);
        $auto->update(['queued_at' => null]);

        Log::info("[Automations] #{$auto->id} \"{$auto->name}\": batch done — sent={$sent}, failed={$failed}");

        // The scheduling command already marks the automation 'completed' (for
        // frequency=once) before dispatching this job, so this check is safe —
        // it fires exactly once, when this batch is that automation's only job.
        if ($auto->status === 'completed' && $auto->notify_email) {
            Mail::to($auto->notify_email)
                ->send(new AutomationCompletedMail($auto, $auto->emails_sent_total, $failed));
        }
    }

    /**
     * Called once all $tries are exhausted — without this, a persistently
     * failing job (e.g. bad SMTP credentials) would leave queued_at stuck
     * forever, making the UI show "sending now" indefinitely.
     */
    public function failed(\Throwable $exception): void
    {
        CampaignAutomation::find($this->automationId)?->update(['queued_at' => null]);
        Log::error("[Automations] #{$this->automationId}: job failed permanently — " . $exception->getMessage());
    }
}
