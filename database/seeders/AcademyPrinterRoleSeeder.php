<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

/**
 * Dedicated restricted Voyager role for the Academy "print student ID
 * cards" job - same pattern as academy-instructor/reviewer/accountant/
 * marketing (no browse_admin; the student-cards screen only requires
 * 'auth', gated for real by AcademyStudentCardController::authorizeStaff()).
 * Idempotent.
 */
class AcademyPrinterRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'academy-printer'],
            ['display_name' => 'Academy Printer']
        );

        $this->command?->info("academy-printer role id={$role->id} ready.");
    }
}
