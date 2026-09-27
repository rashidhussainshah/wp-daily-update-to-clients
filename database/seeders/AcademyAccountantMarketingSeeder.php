<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Amir Sohail (real HR staff) gets the Academy Accountant capability on
 * TOP of his existing HR role, per instruction - Academy fees are the
 * accountant capability's job specifically, not HR's by default, but a
 * person can hold both at once (additive - academy_staff_roles, same
 * pattern as instructor/reviewer). Idempotent.
 *
 * Test accounts for this capability live in AcademyTestAccountsSeeder.
 */
class AcademyAccountantMarketingSeeder extends Seeder
{
    public function run(): void
    {
        if ($amir = User::where('email', 'shanbalouchkhan@gmail.com')->first()) {
            AcademyStaffRole::firstOrCreate(['user_id' => $amir->id, 'capability' => AcademyStaffRole::CAPABILITY_ACCOUNTANT]);
            $this->command?->info("{$amir->name}: accountant capability confirmed (kept existing role: {$amir->role?->name}).");
        } else {
            $this->command?->warn('Skipping Amir Sohail: no user found for shanbalouchkhan@gmail.com.');
        }
    }
}
