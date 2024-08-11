<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ClockifyService;
use Illuminate\Console\Command;

class SyncUsersToClockify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clockify:sync-users';
    protected $description = 'Create all existing users on Clockify and store their clockify_user_id';

    public function handle()
    {
        $clockifyService = app(ClockifyService::class);

        User::whereNull('clockify_user_id')->chunk(100, function ($users) use ($clockifyService) {
            foreach ($users as $user) {
                try {
                    $this->info("Creating Clockify user for {$user->email}");

                    $clockifyUser = $clockifyService->createUser($user->email, $user->name);

                    $user->clockify_user_id = $clockifyUser['id'];
                    $user->save();

                    $this->info("Clockify user created for {$user->email} with ID: {$clockifyUser['id']}");
                } catch (\Exception $e) {
                    $this->error("Failed to create Clockify user for {$user->email}: {$e->getMessage()}");
                }
            }
        });

        $this->info('All users have been synced to Clockify.');
        return 0;
    }
}
