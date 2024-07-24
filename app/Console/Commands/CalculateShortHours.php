<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;
use App\Models\User;
use Carbon\Carbon;

class CalculateShortHours extends Command
{
    protected $signature = 'calculate:shorthours {userId}';
    protected $description = 'Calculate total short hours and fines for each day, week, and month for a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('userId');
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        // Retrieve all check-ins for the user where checkout time is available
        $checkins = Checkin::where('developer_id', $userId)
            ->whereNotNull('checkout_at')
            ->get();

        if ($checkins->isEmpty()) {
            $this->info('No check-in data found for the user.');
            return;
        }

        // Define total office hours based on user type (part-time or full-time)
        $totalOfficeHours = $user->part_time ? 4.5 : 9;

        // Initialize arrays to store short hours and fines
        $dailyShortHours = [];
        $weeklyShortHours = [];
        $monthlyShortHours = [];
        $dailyFines = [];
        $weeklyFines = [];
        $monthlyFines = [];

        foreach ($checkins as $checkin) {
            // Calculate the time spent by the user
            $checkinAt = Carbon::parse($checkin->checkin_at);
            $checkoutAt = Carbon::parse($checkin->checkout_at);
            $hoursSpent = $checkinAt->diffInHours($checkoutAt);
            $minutesSpent = $checkinAt->diffInMinutes($checkoutAt) % 60;

            // Calculate short hours and minutes
            $shortHours = max(0, $totalOfficeHours - $hoursSpent);
            $shortMinutes = max(0, 60 - $minutesSpent);

            // Convert short hours to minutes and calculate total short minutes
            $totalShortMinutes = ($shortHours * 60) + $shortMinutes;

            // Calculate fine based on the rule: PKR 10 for every 5 minutes of shortfall
            $fine = floor($totalShortMinutes / 5) * 10;

            // Extract date and month information
            $date = $checkinAt->format('Y-m-d');
            $month = $checkinAt->format('Y-m');
            $weekOfMonth = $checkinAt->weekOfMonth;

            // Initialize daily, weekly, and monthly arrays if not already set
            if (!isset($dailyShortHours[$date])) {
                $dailyShortHours[$date] = 0;
                $dailyFines[$date] = 0;
            }
            if (!isset($weeklyShortHours[$month][$weekOfMonth])) {
                $weeklyShortHours[$month][$weekOfMonth] = 0;
                $weeklyFines[$month][$weekOfMonth] = 0;
            }
            if (!isset($monthlyShortHours[$month])) {
                $monthlyShortHours[$month] = 0;
                $monthlyFines[$month] = 0;
            }

            // Accumulate short hours and fines for daily, weekly, and monthly periods
            $dailyShortHours[$date] += $totalShortMinutes;
            $dailyFines[$date] += $fine;

            $weeklyShortHours[$month][$weekOfMonth] += $totalShortMinutes;
            $weeklyFines[$month][$weekOfMonth] += $fine;

            $monthlyShortHours[$month] += $totalShortMinutes;
            $monthlyFines[$month] += $fine;
        }

        // Output the results
        $this->info("Total short hours and fines for user ID: $userId");

        // Output daily short hours and fines
        $this->info("\nDaily Short Hours and Fines:");
        foreach ($dailyShortHours as $date => $totalShortMinutes) {
            $fine = $dailyFines[$date];
            $hoursShort = floor($totalShortMinutes / 60);
            $minutesShort = $totalShortMinutes % 60;
            $fineCalculation = floor($totalShortMinutes / 5);
            $formula = "Fine = floor($totalShortMinutes / 5) * 10 = PKR $fine ($fineCalculation)";
            $this->info("Date $date:\n  Short Hours: $hoursShort hours and $minutesShort minutes\n  Fine: PKR $fine\n  Calculation: $formula\n");
        }

        // Output weekly short hours and fines
        $this->info("\nWeekly Short Hours and Fines:");
        foreach ($weeklyShortHours as $month => $weeks) {
            foreach ($weeks as $week => $totalShortMinutes) {
                $fine = $weeklyFines[$month][$week];
                $hoursShort = floor($totalShortMinutes / 60);
                $minutesShort = $totalShortMinutes % 60;
                $fineCalculation = floor($totalShortMinutes / 5);
                $formula = "Fine = floor($totalShortMinutes / 5) * 10 = PKR $fine ($fineCalculation)";
                $this->info("Month $month, Week $week:\n  Short Hours: $hoursShort hours and $minutesShort minutes\n  Fine: PKR $fine\n  Calculation: $formula\n");
            }
        }

        // Output monthly short hours and fines
        $this->info("\nMonthly Short Hours and Fines:");
        foreach ($monthlyShortHours as $month => $totalShortMinutes) {
            $fine = $monthlyFines[$month];
            $hoursShort = floor($totalShortMinutes / 60);
            $minutesShort = $totalShortMinutes % 60;
            $fineCalculation = floor($totalShortMinutes / 5);
            $formula = "Fine = floor($totalShortMinutes / 5) * 10 = PKR $fine ($fineCalculation)";
            $this->info("Month $month:\n  Short Hours: $hoursShort hours and $minutesShort minutes\n  Fine: PKR $fine\n  Calculation: $formula\n");
        }
    }
}
