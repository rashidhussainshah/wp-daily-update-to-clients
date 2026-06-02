<?php

namespace App\Jobs;

use App\Mail\MarketingCampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public int $campaignId,
        public array $recipients   // [['email'=>'...','name'=>'...']]
    ) {}

    public function handle(): void
    {
        $campaign = EmailCampaign::find($this->campaignId);
        if (!$campaign) {
            return;
        }

        // Config::set('mail.mailers.smtp', [
        //     'transport'  => 'smtp',
        //     'host'       => setting('marketing.smtp_host')       ?: 'smtp.titan.email',
        //     'port'       => (int) (setting('marketing.smtp_port') ?: 465),
        //     'encryption' => setting('marketing.smtp_encryption') ?: 'ssl',
        //     'username'   => setting('marketing.smtp_username')   ?: '',
        //     'password'   => setting('marketing.smtp_password')   ?: '',
        //     'timeout'    => null,
        //     'auth_mode'  => null,
        // ]);
        // app('mail.manager')->purge('smtp');

        $delayMs = (int) (setting('marketing.delay_ms') ?? 100);

        foreach ($this->recipients as $recipient) {
            $email = $recipient['email'];
            $name  = $recipient['name'] ?? '';

            try {
                $alreadySent = EmailCampaignLog::where('campaign_id', $this->campaignId)
                    ->where('email', $email)
                    ->where('status', 'sent')
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                Mail::to($email, $name)
                    ->send(new MarketingCampaignMail($campaign, $name));

                EmailCampaignLog::updateOrCreate(
                    ['campaign_id' => $this->campaignId, 'email' => $email],
                    ['name' => $name, 'status' => 'sent', 'sent_at' => now(), 'error' => null]
                );

                $campaign->increment('sent_count');

                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            } catch (\Throwable $e) {
                Log::warning("Campaign {$this->campaignId} failed for {$email}: " . $e->getMessage());

                EmailCampaignLog::updateOrCreate(
                    ['campaign_id' => $this->campaignId, 'email' => $email],
                    ['name' => $name, 'status' => 'failed', 'error' => $e->getMessage()]
                );

                $campaign->increment('failed_count');
            }
        }
    }

}
