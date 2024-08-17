<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\ClockifyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GetClockifyTimeEntries extends Command
{
    protected $signature = 'clockify:get-time-entries {email}';
    protected $description = 'Get today\'s Clockify time entries for a specified user email';

    public function handle()
    {
        $email = $this->argument('email');

        // Fetch the user from the portal database
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No portal user found with email: $email");
            return 1;
        }

        // Ensure the user has a Clockify user ID
        if (!$user->clockify_user_id) {
            $this->error("The user with email $email does not have a Clockify user ID associated.");
            return 1;
        }

        // Get today's time entries from Clockify
        $clockifyService = app(ClockifyService::class);
        // Format the start and end dates to the required UTC format
        $startDate = Carbon::today()->startOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T00:00:00Z';
        $endDate = Carbon::today()->endOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T23:59:59Z';

        // Log the formatted dates
        Log::info('Fetching time entries from Clockify', [
            'user_id' => $user->clockify_user_id,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        try {
            $timeEntries = $clockifyService->getUserTimeEntries($user->clockify_user_id, $startDate, $endDate);

            if (empty($timeEntries)) {
                $this->info("No time entries found for today.");
                return 0;
            }

            // Display the time entries
            foreach ($timeEntries as $entry) {
                // Show the full entry object (optional)
                $this->info(print_r($entry, true));

                $description = $entry['description'] ?? 'No description';
                $project = $entry['project']['name'] ?? 'No project';
                $start = Carbon::parse($entry['timeInterval']['start']);
                $end = Carbon::parse($entry['timeInterval']['end']);
                $durationMinutes = $start->diffInMinutes($end);

                $hours = intdiv($durationMinutes, 60);
                $minutes = $durationMinutes % 60;

                $this->info("Project: " . $project);
                $this->info("Description: " . $description);
                $this->info("Start: " . $start->format('Y-m-d H:i:s'));
                $this->info("End: " . $end->format('Y-m-d H:i:s'));
                $this->info("Time Spent: " . $hours . " hours and " . $minutes . " minutes");
                $this->info("---");
            }
        } catch (\Exception $e) {
            $this->error("Failed to retrieve time entries: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
