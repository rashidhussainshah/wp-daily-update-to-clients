<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ClockifyService;
use Illuminate\Console\Command;

class SyncClockifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-users-to-clockify';
    protected $description = 'Sync Clockify users with portal users and save Clockify user ID in portal';

    public function handle()
    {
        $clockifyService = app(ClockifyService::class);
        $clockifyUsers = $clockifyService->getAllUsers();

        foreach ($clockifyUsers as $clockifyUser) {
            $portalUser = User::where('email', $clockifyUser['email'])->first();

            if ($portalUser) {
                $portalUser->clockify_user_id = $clockifyUser['id'];
                $portalUser->save();

                $this->info("Clockify user ID saved for portal user: {$portalUser->email}");
            } else {
                $this->warn("No matching portal user found for Clockify user: {$clockifyUser['email']}");
            }
        }

        $this->info('Clockify users synchronization completed.');
        return 0;
    }

}
