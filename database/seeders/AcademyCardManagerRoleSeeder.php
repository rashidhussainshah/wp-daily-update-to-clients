<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

/**
 * Dedicated restricted Voyager role for the Academy "assemble print
 * batches" job - separate from academy-printer, which actually prints them.
 * No browse_admin; the card-batches screen only requires 'auth', gated for
 * real by AcademyCardBatchController::authorizeStaff(). Idempotent.
 */
class AcademyCardManagerRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'academy-card-manager'],
            ['display_name' => 'Academy Card Manager']
        );

        $this->command?->info("academy-card-manager role id={$role->id} ready.");
    }
}
