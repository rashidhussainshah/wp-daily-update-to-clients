<?php

namespace App\Console\Commands;

use App\Models\Checkin;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMissingCheckins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missing-checkins {developer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing checkins and checkouts for the specified developer in the current month';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Get the developer_id from the passed argument
        $developerId = $this->argument('developer_id');

        // Get the first and last day of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get all the dates for the current month
        $datesInCurrentMonth = [];
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $datesInCurrentMonth[] = $date->toDateString();  // Add each date in the month to the array
        }

        // Get all check-ins for the specified developer in the current month
        $checkins = Checkin::where('developer_id', $developerId)
            ->whereBetween('checkin_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(checkin_at) as date, developer_id, checkin_at, checkout_at')
            ->get()
            ->groupBy('date');

        // Initialize the result variables
        $missingDays = [];
        $missingCheckins = 0;
        $missingCheckouts = 0;
        $checkinsWithoutCheckout = [];

        // Fetch the developer's name for easier reference
        $developer = Checkin::where('developer_id', $developerId)->first()?->developer;

        // Check if the developer exists
        $developerName = $developer ? $developer->name : 'Unknown Developer';

        // Loop through the dates of the current month and check if each day is missing
        foreach ($datesInCurrentMonth as $date) {
            // If there's no check-in record for this day
            if (!isset($checkins[$date]) || $checkins[$date]->count() == 0) {
                $missingDays[] = $date;
                $missingCheckins++;  // Increment missing check-ins
                $missingCheckouts++;  // Increment missing check-outs since no check-in occurred
            } else {
                // Check if there are check-ins but no check-out for that date
                foreach ($checkins[$date] as $checkin) {
                    if (!$checkin->checkout_at) {
                        // If the user checked in but didn't check out, add to the list
                        $checkinsWithoutCheckout[] = $checkin;
                        $missingCheckouts++;  // Increment missing check-out
                    }
                }
            }
        }

        // Output the results
        $this->info("Developer: $developerName (ID: $developerId)");
        $this->info("Missing check-ins: $missingCheckins");
        $this->info("Missing check-outs: $missingCheckouts");

        // Missing days
        $this->info("Missing days:");
        foreach ($missingDays as $missingDay) {
            $this->line(" - $missingDay");
        }

        // Check-ins without check-out
        if (count($checkinsWithoutCheckout) > 0) {
            $this->info("Check-ins without check-out:");
            foreach ($checkinsWithoutCheckout as $checkin) {
                $this->line(" - Developer: $developerName, Check-in: {$checkin->checkin_at->format('Y-m-d H:i:s')}");
            }
        } else {
            $this->info("No check-ins without check-out.");
        }

        return 0;
    }
}
