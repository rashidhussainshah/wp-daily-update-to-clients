<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\EOD;
use App\Models\Fine;
use Carbon\Carbon;

class CheckEODAndAddFine extends Command
{
    protected $signature = 'eod:check';

    protected $description = 'Check EOD and add fine if not submitted';

    public function handle()
    {
        $developers = User::onlyDeveloper()->get();
        $today = Carbon::today();

        foreach ($developers as $developer) {
            // Check if there's no EOD entry for the developer for today
            $eod = EOD::where('developer_id', $developer->id)
                ->whereDate('created_at', $today)
                ->first();

            if (!$eod) {
                // Check if a fine already exists for the same user and date
                $existingFine = Fine::where('user_id', $developer->id)
                    ->whereDate('date', $today)
                    ->where('reason', 'eod')
                    ->exists();

                if (!$existingFine) {
                    // Add a fine of Rs. 50 to the fine table
                    Fine::create([
                        'user_id' => $developer->id,
                        'amount' => 50,
                        'reason' => 'eod',
                        'note' => 'No EOD entry for ' . $today->toDateString(),
                        'date' => $today,
                        // Other fine details if needed
                    ]);

                    // You might also want to notify or log this action
                    $this->info("Fine added ({$today}) for Name: {$developer->name}");
                }
            }
        }
    }

}
