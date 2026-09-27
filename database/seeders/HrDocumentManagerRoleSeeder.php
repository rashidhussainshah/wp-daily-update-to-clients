<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

/**
 * Dedicated restricted Voyager role for staff whose only job is running the
 * HR document builder (experience/relieving/internship letters etc.) -
 * someone who should NOT get the rest of HR's access (leaves, financials,
 * academy fees...). No browse_admin; the hr-documents screens only require
 * 'auth', gated for real by each controller's authorizeStaff(). The full
 * 'HR' role keeps access too (checked alongside this one everywhere).
 * Idempotent. Production-safe - creates only the role, no accounts. The
 * test account for this role lives in HrDocumentManagerTestAccountSeeder
 * (dev-only, not part of ProductionHrDocumentsSeeder).
 */
class HrDocumentManagerRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'hr-document-manager'],
            ['display_name' => 'HR Document Manager']
        );

        $this->command?->info("hr-document-manager role id={$role->id} ready.");
    }
}
