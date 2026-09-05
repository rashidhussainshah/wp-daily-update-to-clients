<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Setting;

/**
 * One-time org setup for the Academy: the dedicated "IT Academy Student"
 * Voyager role (so students never collide with the legacy RYK/student
 * roles), and the additive instructor/reviewer capabilities for staff who
 * already hold a primary Voyager role. Idempotent - safe to re-run.
 *
 * Reviewer/instructor assignments below reflect real WebPenter staff at the
 * time the Academy launched; change or extend them via
 * admin/academy-staff-roles-equivalent settings, or directly in Voyager's
 * Users screen going forward - this seeder only establishes the starting
 * state, it does not need to run again after that.
 */
class AcademyStaffSetupSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'it-academy-student'],
            ['display_name' => 'IT Academy Student']
        );

        Setting::updateOrCreate(
            ['key' => 'academy.it_academy_student_role_id'],
            [
                'display_name' => 'IT Academy Student Role ID',
                'value' => $role->id,
                'type' => 'text',
                'order' => 1,
                'group' => 'Academy',
            ]
        );
        $this->command?->info("IT Academy Student role id={$role->id}, setting saved.");

        $assignments = [
            ['email' => 'sainmehtab15@gmail.com', 'capability' => AcademyStaffRole::CAPABILITY_INSTRUCTOR], // Mehtab sain
            ['email' => 'ahmadraza4119@gmail.com', 'capability' => AcademyStaffRole::CAPABILITY_REVIEWER], // Ahmad Raza
            ['email' => 'alihasanwebpenter@gmail.com', 'capability' => AcademyStaffRole::CAPABILITY_REVIEWER], // Ali Hassan
        ];

        foreach ($assignments as $assignment) {
            $user = User::where('email', $assignment['email'])->first();

            if (!$user) {
                $this->command?->warn("Skipping staff assignment: no user found for {$assignment['email']}.");
                continue;
            }

            AcademyStaffRole::firstOrCreate([
                'user_id' => $user->id,
                'capability' => $assignment['capability'],
            ]);

            $this->command?->info("{$user->name}: {$assignment['capability']} capability confirmed.");
        }
    }
}
