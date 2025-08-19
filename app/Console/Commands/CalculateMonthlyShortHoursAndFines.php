<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;
use App\Models\User;
use App\Models\Leave;
use App\Models\Fine;
use Carbon\Carbon;

class CalculateMonthlyShortHoursAndFines extends Command
{
    protected $signature = 'calculate:monthly-short-hours {userId} {month} {year}';
    protected $description = 'Calculate monthly short hours and fines for a specific user, month, and year';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('userId');
        $month = $this->argument('month');
        $year = $this->argument('year');

        // Validate user
        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        // Validate month and year
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $this->error('Invalid month. Please provide a number between 1 and 12.');
            return 1;
        }

        if (!is_numeric($year) || $year < 2020 || $year > 2030) {
            $this->error('Invalid year. Please provide a year between 2020 and 2030.');
            return 1;
        }

        // Get user's salary and required hours (you'll need to add these fields to users table)
        $salary = $user->salary ?? 0;
        $totalRequiredHours = $user->total_required_hours ?? 0;

        if ($salary <= 0 || $totalRequiredHours <= 0) {
            $this->error('User salary or total required hours not configured. Please set these values in the user profile.');
            return 1;
        }

        $this->info("Calculating short hours and fines for {$user->name} - {$month}/{$year}");
        $this->info("Salary: PKR {$salary}");
        $this->info("Total Required Hours: {$totalRequiredHours}");

        // Get the start and end dates for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all check-ins for the month
        $checkins = Checkin::where('developer_id', $userId)
            ->whereBetween('checkin_at', [$startDate, $endDate])
            ->get();

        // Get all leaves for the month
        $leaves = Leave::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('date');

        $totalShortHours = 0;
        $totalShortMinutes = 0;
        $dailyDetails = [];

        // Iterate through each day of the month
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek;
            
            // Skip Sundays (day 0)
            if ($dayOfWeek == Carbon::SUNDAY) {
                $currentDate->addDay();
                continue;
            }

            // Check if user has a leave for this day
            $hasLeave = $leaves->has($dateString);
            
            // Get check-in for this day
            $checkin = $checkins->where('checkin_at', '>=', $currentDate->startOfDay())
                               ->where('checkin_at', '<=', $currentDate->endOfDay())
                               ->first();

            $requiredHours = ($dayOfWeek == Carbon::SATURDAY) ? 4.5 : 9;
            $requiredMinutes = $requiredHours * 60;
            
            $actualMinutes = 0;
            $shortMinutes = 0;
            $reason = '';

            if ($checkin && $checkin->checkout_at) {
                // User has both check-in and check-out
                $checkinTime = Carbon::parse($checkin->checkin_at);
                $checkoutTime = Carbon::parse($checkin->checkout_at);
                $actualMinutes = $checkinTime->diffInMinutes($checkoutTime);
                $shortMinutes = max(0, $requiredMinutes - $actualMinutes);
                $reason = $shortMinutes > 0 ? 'Short hours' : 'Complete';
            } elseif ($checkin && !$checkin->checkout_at) {
                // User has check-in but no check-out
                if ($hasLeave) {
                    $reason = 'Leave taken';
                    $shortMinutes = 0;
                } else {
                    $reason = 'Missing checkout - considered as 8 hours short';
                    $shortMinutes = 8 * 60; // 8 hours in minutes
                }
            } else {
                // No check-in at all
                if ($hasLeave) {
                    $reason = 'Leave taken';
                    $shortMinutes = 0;
                } else {
                    $reason = 'No check-in - considered as 8 hours short';
                    $shortMinutes = 8 * 60; // 8 hours in minutes
                }
            }

            if ($shortMinutes > 0) {
                $totalShortMinutes += $shortMinutes;
                $totalShortHours += $shortMinutes / 60;
            }

            $dailyDetails[] = [
                'date' => $dateString,
                'day' => $currentDate->format('l'),
                'required_hours' => $requiredHours,
                'actual_hours' => round($actualMinutes / 60, 2),
                'short_hours' => round($shortMinutes / 60, 2),
                'short_minutes' => $shortMinutes,
                'reason' => $reason,
                'has_leave' => $hasLeave ? 'Yes' : 'No'
            ];

            $currentDate->addDay();
        }

        // Calculate fine
        $fineAmount = $this->calculateFine($totalShortMinutes, $salary, $totalRequiredHours);

        // Display results
        $this->displayResults($dailyDetails, $totalShortHours, $totalShortMinutes, $fineAmount, $user, $month, $year);

        // Ask if user wants to save the fine
        if ($this->confirm('Do you want to save this fine to the database?')) {
            $this->saveFine($userId, $fineAmount, $totalShortMinutes, $month, $year);
        }

        return 0;
    }

    private function calculateFine($totalShortMinutes, $salary, $totalRequiredHours)
    {
        // Calculate fine based on short hours proportion of salary
        $shortHours = $totalShortMinutes / 60;
        $fineAmount = ($shortHours / $totalRequiredHours) * $salary;
        
        return round($fineAmount, 2);
    }

    private function displayResults($dailyDetails, $totalShortHours, $totalShortMinutes, $fineAmount, $user, $month, $year)
    {
        $this->info("\n" . str_repeat('=', 80));
        $this->info("MONTHLY SHORT HOURS AND FINE CALCULATION");
        $this->info(str_repeat('=', 80));
        $this->info("User: {$user->name}");
        $this->info("Period: " . Carbon::create($year, $month, 1)->format('F Y'));
        $this->info(str_repeat('-', 80));

        // Display daily details
        $this->info("\nDaily Breakdown:");
        $this->info(str_pad('Date', 12) . str_pad('Day', 10) . str_pad('Required', 10) . 
                   str_pad('Actual', 10) . str_pad('Short', 10) . str_pad('Reason', 30));
        $this->info(str_repeat('-', 82));

        foreach ($dailyDetails as $detail) {
            if ($detail['short_minutes'] > 0) {
                $this->info(str_pad($detail['date'], 12) . 
                           str_pad($detail['day'], 10) . 
                           str_pad($detail['required_hours'] . 'h', 10) . 
                           str_pad($detail['actual_hours'] . 'h', 10) . 
                           str_pad($detail['short_hours'] . 'h', 10) . 
                           str_pad($detail['reason'], 30));
            }
        }

        $this->info(str_repeat('=', 80));
        $this->info("SUMMARY");
        $this->info(str_repeat('=', 80));
        $this->info("Total Short Hours: " . round($totalShortHours, 2) . " hours");
        $this->info("Total Short Minutes: {$totalShortMinutes} minutes");
        $this->info("Calculated Fine: PKR " . number_format($fineAmount, 2));
        $this->info(str_repeat('=', 80));
    }

    private function saveFine($userId, $fineAmount, $totalShortMinutes, $month, $year)
    {
        try {
            $fine = Fine::create([
                'user_id' => $userId,
                'amount' => $fineAmount,
                'reason' => "Monthly short hours fine for " . Carbon::create($year, $month, 1)->format('F Y') . 
                           " - {$totalShortMinutes} minutes short",
                'date' => Carbon::now()->format('Y-m-d'),
                'note' => "Auto-calculated fine for short hours in " . Carbon::create($year, $month, 1)->format('F Y')
            ]);

            $this->info("Fine saved successfully with ID: {$fine->id}");
        } catch (\Exception $e) {
            $this->error("Failed to save fine: " . $e->getMessage());
        }
    }
}

