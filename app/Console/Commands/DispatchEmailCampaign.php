<?php

namespace App\Console\Commands;

use App\Mail\MarketingCampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use TCG\Voyager\Models\Role;

class DispatchEmailCampaign extends Command
{
    protected $signature = 'campaign:dispatch
                            {campaign_id : ID of the email campaign}
                            {--limit= : Max recipients (default: all)}
                            {--dry-run : Show count without sending}
                            {--delay=100 : Milliseconds between emails to avoid rate-limits}';

    protected $description = 'Dispatch a marketing email campaign to users with the target role';

    public function handle(): int
    {
        $campaign = EmailCampaign::find($this->argument('campaign_id'));

        if (!$campaign) {
            $this->error('Campaign not found.');
            return 1;
        }

        if ($campaign->isSent()) {
            $this->error("Campaign already sent at {$campaign->sent_at}.");
            return 1;
        }

        $role = Role::where('name', $campaign->target_role)->first();
        if (!$role) {
            $this->error("Role '{$campaign->target_role}' not found.");
            return 1;
        }

        $query = User::where('role_id', $role->id)->whereNotNull('email');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        if ($limit) {
            $query->limit($limit);
        }
        $total = $query->count();

        $this->info("Campaign: {$campaign->name}");
        $this->info("Subject:  {$campaign->subject}");
        $this->info("Recipients: {$total} users with role '{$campaign->target_role}'");

        if ($this->option('dry-run')) {
            $this->warn('[Dry run] No emails sent.');
            return 0;
        }

        if (!$this->confirm("Send {$total} emails?")) {
            return 0;
        }

        $campaign->update(['status' => 'sending']);

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $sent = 0;
        $failed = 0;
        $delay = (int) $this->option('delay');

        $query->chunk(200, function ($users) use ($campaign, &$sent, &$failed, $bar, $delay) {
            foreach ($users as $user) {
                try {
                    $alreadySent = EmailCampaignLog::where('campaign_id', $campaign->id)
                        ->where('email', $user->email)
                        ->where('status', 'sent')
                        ->exists();

                    if ($alreadySent) {
                        $bar->advance();
                        continue;
                    }

                    Mail::to($user->email, $user->name)
                        ->send(new MarketingCampaignMail($campaign, $user->name ?? ''));

                    EmailCampaignLog::updateOrCreate(
                        ['campaign_id' => $campaign->id, 'email' => $user->email],
                        ['name' => $user->name, 'status' => 'sent', 'sent_at' => now()]
                    );
                    $sent++;

                    if ($delay > 0) {
                        usleep($delay * 1000);
                    }
                } catch (\Throwable $e) {
                    EmailCampaignLog::updateOrCreate(
                        ['campaign_id' => $campaign->id, 'email' => $user->email],
                        ['name' => $user->name, 'status' => 'failed', 'error' => $e->getMessage()]
                    );
                    $failed++;
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();

        $campaign->update([
            'status'       => 'sent',
            'sent_at'      => now(),
            'sent_count'   => $sent,
            'failed_count' => $failed,
        ]);

        $this->info("Done. Sent: {$sent} | Failed: {$failed}");

        return 0;
    }
}
