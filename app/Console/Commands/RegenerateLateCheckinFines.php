<?php

namespace App\Console\Commands;

use App\Models\Checkin;
use App\Models\Fine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RegenerateLateCheckinFines extends Command
{
    protected $signature = 'fines:regenerate-late-checkin {month} {user_id}';

    protected $description = 'Regenerate late check-in fines for a specific month and user';

    public function handle()
    {
        $month = $this->argument('month'); // Format: YYYY-MM (e.g., 2026-01)
        $userId = $this->argument('user_id');

        Log::info("Starting late check-in fines regeneration for month: {$month}, user_id: {$userId}");
        $this->info("Starting late check-in fines regeneration for month: {$month}, user_id: {$userId}");

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            Log::error("RegenerateLateCheckinFines: User with ID {$userId} not found.");
            return 1;
        }

        $this->info("Processing user: {$user->name} (ID: {$user->id})");
        $this->info("is_development_team_member: " . ($user->is_development_team_member ? 'Yes' : 'No'));
        Log::info("Processing user: {$user->name}, is_development_team_member: " . ($user->is_development_team_member ? 'Yes' : 'No'));

        // Check if late check-in fine is enabled
        $lateCheckinFineEnabled = setting('checkin.late_checkin_fine_enabled', false);
        if (!$lateCheckinFineEnabled) {
            $this->warn("Late check-in fine is not enabled in settings. Proceeding anyway for regeneration.");
            Log::warning("Late check-in fine setting is disabled, but proceeding with regeneration.");
        }

        // Get fine amount from settings
        $fineAmount = setting('checkin.late_fine_amount_enabled');
        if (!$fineAmount) {
            $this->error("Fine amount is not configured in settings (checkin.late_fine_amount_enabled).");
            Log::error("RegenerateLateCheckinFines: Fine amount not configured.");
            return 1;
        }

        $this->info("Fine amount from settings: Rs. {$fineAmount}");

        // Parse month to get start and end dates
        try {
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        } catch (\Exception $e) {
            $this->error("Invalid month format. Please use YYYY-MM (e.g., 2026-01)");
            Log::error("RegenerateLateCheckinFines: Invalid month format - {$month}");
            return 1;
        }

        $this->info("Date range: {$startDate->toDateString()} to {$endDate->toDateString()}");
        Log::info("Date range: {$startDate->toDateString()} to {$endDate->toDateString()}");

        // Get user's allowed check-in time
        $allowedCheckinTime = $user->checkin_time ?? '10:30';
        $this->info("Allowed check-in time for user: {$allowedCheckinTime}");
        Log::info("Allowed check-in time: {$allowedCheckinTime}");

        // Get all check-ins for the user in the specified month
        $checkins = Checkin::where('developer_id', $userId)
            ->whereDate('checkin_at', '>=', $startDate)
            ->whereDate('checkin_at', '<=', $endDate)
            ->orderBy('checkin_at')
            ->get();

        $this->info("Found {$checkins->count()} check-ins for the month.");
        Log::info("Found {$checkins->count()} check-ins for month {$month}");

        $finesCreated = 0;
        $finesSkipped = 0;

        foreach ($checkins as $checkin) {
            $checkinAt = Carbon::parse($checkin->checkin_at);
            $checkinDate = $checkinAt->toDateString();
            $allowedTime = Carbon::parse($checkinDate . ' ' . $allowedCheckinTime);

            $this->line("---");
            $this->info("Checking: {$checkinDate}");
            $this->info("  Check-in time: {$checkinAt->format('H:i:s')}");
            $this->info("  Allowed time: {$allowedTime->format('H:i:s')}");

            // Check if check-in was late
            if ($checkinAt->greaterThan($allowedTime)) {
                $this->warn("  Late check-in detected!");
                Log::info("Late check-in on {$checkinDate} at {$checkinAt->format('H:i:s')}");

                // Check if fine already exists for this date
                $existingFine = Fine::where('user_id', $userId)
                    ->whereDate('date', $checkinDate)
                    ->where('reason', 'Late check-in')
                    ->exists();

                if ($existingFine) {
                    $this->info("  Fine already exists for this date. Skipping.");
                    Log::info("Fine already exists for {$checkinDate}. Skipped.");
                    $finesSkipped++;
                } else {
                    // Create the fine
                    Fine::create([
                        'user_id' => $userId,
                        'amount' => $fineAmount,
                        'reason' => 'Late check-in',
                        'note' => "Fine applied due to late check-in (regenerated via command)",
                        'date' => $checkinAt,
                    ]);

                    $this->info("  Fine of Rs. {$fineAmount} created.");
                    Log::info("Fine created for {$checkinDate}: Rs. {$fineAmount}");
                    $finesCreated++;
                }
            } else {
                $this->info("  On-time check-in. No fine needed.");
            }
        }

        $this->line("---");
        $this->info("Summary:");
        $this->info("  Total check-ins processed: {$checkins->count()}");
        $this->info("  Fines created: {$finesCreated}");
        $this->info("  Fines skipped (already exist): {$finesSkipped}");

        Log::info("RegenerateLateCheckinFines completed. Created: {$finesCreated}, Skipped: {$finesSkipped}");

        return 0;
    }
}
