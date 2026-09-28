<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

/**
 * The four original dedicated restricted Voyager roles for staff whose
 * only Academy job is one of these (no browse_admin) - academy-printer,
 * academy-card-manager, and hr-document-manager each got their own seeder
 * later on, but these four were only ever created ad hoc via tinker in the
 * dev database while building this feature, never committed anywhere.
 * AcademyTestAccountsSeeder references them by name
 * (Role::where('name', 'academy-instructor')->value('id')) and silently
 * got null on any environment where they don't already exist - this is
 * that missing piece. Idempotent.
 */
class AcademyStaffRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'academy-instructor' => 'Academy Instructor',
            'academy-reviewer' => 'Academy Reviewer',
            'academy-accountant' => 'Academy Accountant',
            'academy-marketing' => 'Academy Marketing',
        ];

        foreach ($roles as $name => $displayName) {
            $role = Role::firstOrCreate(['name' => $name], ['display_name' => $displayName]);
            $this->command?->info("{$name} role id={$role->id} ready.");
        }
    }
}
