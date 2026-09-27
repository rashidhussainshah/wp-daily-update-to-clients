<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Real-staff Academy Marketing capability grants - kept as its own seeder
 * rather than editing AcademyStaffSetupSeeder/AcademyAccountantMarketingSeeder
 * (already run, never edited after the fact). Additive - each of these
 * keeps their existing primary role and any other Academy capability
 * (e.g. Ahmad Raza keeps his Reviewer capability alongside this one).
 * Idempotent.
 */
class AcademyMarketingResourceSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            // Rumaisha Asif - WebPenter's marketing resource: certificate
            // gallery/sharing + AI post suggestions.
            'rumaishaasifwp@gmail.com',
            // Ahmad Raza - already the Academy project Reviewer; also
            // handles certificate assignment/issuance review as an
            // experienced resource.
            'ahmadraza4119@gmail.com',
        ];

        foreach ($assignments as $email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->command?->warn("Skipping marketing capability: no user found for {$email}.");
                continue;
            }

            AcademyStaffRole::firstOrCreate([
                'user_id' => $user->id,
                'capability' => AcademyStaffRole::CAPABILITY_MARKETING,
            ]);

            $this->command?->info("{$user->name}: marketing capability confirmed (kept existing role: {$user->role?->name}).");
        }
    }
}
