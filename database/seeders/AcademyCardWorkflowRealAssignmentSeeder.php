<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Amir Sohail (shanbalouchkhan@gmail.com) handles the whole student ID
 * card workflow end to end - assembling print batches (Card Manager) and
 * actually printing them (Printer) - on top of his existing HR role and
 * Instructor/Accountant capabilities. Real replacement for the
 * test.cardmanager@webpenter.test / test.printer@webpenter.test test
 * accounts, which stay in place for local testing only. Idempotent.
 */
class AcademyCardWorkflowRealAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $amir = User::where('email', 'shanbalouchkhan@gmail.com')->first();

        if (!$amir) {
            $this->command?->warn('Skipping card workflow assignment: no user found for shanbalouchkhan@gmail.com.');
            return;
        }

        foreach ([AcademyStaffRole::CAPABILITY_CARD_MANAGER, AcademyStaffRole::CAPABILITY_PRINTER] as $capability) {
            AcademyStaffRole::firstOrCreate(['user_id' => $amir->id, 'capability' => $capability]);
        }

        $this->command?->info("{$amir->name}: card_manager + printer capabilities confirmed.");
    }
}
