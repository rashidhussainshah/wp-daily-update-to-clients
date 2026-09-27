<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\AcademyTrack;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Realigns instructor coverage to match each staff member's actual skills:
 * - Ali Hassan: business development + Python/AI-ML.
 * - Ayub Khokhar: business development (co-instructor, alongside Ali).
 * - Ahmad Raza: PHP/Laravel, WordPress, React/React Native, full stack.
 * - Mehtab Sain: narrowed to just Frontend Fundamentals + PHP & WordPress
 *   Developer (previously also defaulted on Python/AI-ML and Business
 *   Development, both reassigned above).
 *
 * Kept as its own seeder rather than editing AcademyStaffSetupSeeder /
 * AcademyBeginnerTracksInstructorSeeder (already run, never edited after
 * the fact). default_instructor_id only changes who a NEW enrollment gets
 * by default - it never moves anyone already enrolled. Idempotent.
 */
class AcademyInstructorRealignmentSeeder extends Seeder
{
    public function run(): void
    {
        $ali = User::where('email', 'alihasanwebpenter@gmail.com')->first();
        $ayub = User::where('email', 'ayubkhokhar786@gmail.com')->first();
        $ahmad = User::where('email', 'ahmadraza4119@gmail.com')->first();
        $mehtab = User::where('email', 'sainmehtab15@gmail.com')->first();

        foreach ([$ali, $ayub, $ahmad] as $user) {
            if (!$user) {
                continue;
            }
            AcademyStaffRole::firstOrCreate(['user_id' => $user->id, 'capability' => AcademyStaffRole::CAPABILITY_INSTRUCTOR]);
            $this->command?->info("{$user->name}: instructor capability confirmed.");
        }

        if ($ali) {
            AcademyTrack::whereIn('name', ['Python & AI/ML Engineer', 'Business Development & Freelancing'])
                ->update(['default_instructor_id' => $ali->id]);
            $this->command?->info('Python & AI/ML Engineer, Business Development & Freelancing: default instructor set to Ali Hassan.');
        }

        if ($ahmad) {
            AcademyTrack::whereIn('name', [
                'Full Stack Laravel/PHP Developer',
                'WordPress Developer & Customization Specialist',
                'React / React Native Developer',
            ])->update(['default_instructor_id' => $ahmad->id]);
            $this->command?->info('Full Stack Laravel/PHP Developer, WordPress Developer & Customization Specialist, React / React Native Developer: default instructor set to Ahmad Raza.');
        }

        if ($mehtab) {
            AcademyTrack::whereIn('name', ['Frontend Fundamentals: HTML, CSS, JavaScript & Bootstrap', 'PHP & WordPress Developer'])
                ->update(['default_instructor_id' => $mehtab->id]);
            $this->command?->info('Frontend Fundamentals, PHP & WordPress Developer: default instructor confirmed as Mehtab Sain.');
        }

        if (!$ali) {
            $this->command?->warn('Skipping Ali Hassan track assignment: no user found for alihasanwebpenter@gmail.com.');
        }
        if (!$ayub) {
            $this->command?->warn('Skipping Ayub Khokhar instructor capability: no user found for ayubkhokhar786@gmail.com.');
        }
        if (!$ahmad) {
            $this->command?->warn('Skipping Ahmad Raza track assignment: no user found for ahmadraza4119@gmail.com.');
        }
    }
}
