<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('inside schedule run');
        // Get current time
        $current_time = Carbon::now();

// Get timezone
        $timezone = $current_time->getTimezone();

// Log current time with timezone
        Log::info("Current time with timezone: " . $current_time->format('Y-m-d H:i:s') . ' ' . $timezone);
        $schedule->command('eod:check')->everyMinute();
        $schedule->command('automations:process')->everyMinute()->withoutOverlapping();
//            ->timezone('Asia/Karachi') // Set timezone to Pakistan Standard Time
//            ->dailyAt('22:00'); // Run daily at 10 PM (22:00)
//            ->everyMinute(); // Run daily at 10 PM (22:00)
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
