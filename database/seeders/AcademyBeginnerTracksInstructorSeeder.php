<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\AcademyTrack;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Amir Sohail (shanbalouchkhan@gmail.com) instructs the two beginner-facing
 * tracks - Digital & AI Foundations (MS Office, Excel, PowerPoint, basic AI)
 * and YouTube & Content Automation - on top of his existing Accountant
 * capability. Kept as its own seeder rather than editing
 * AcademyAccountantMarketingSeeder (already run, never edited after the
 * fact) or AcademyGithubStageAndNewTracksSeeder (which originally set these
 * tracks' default_instructor_id to Mehtab - reassigned here per instruction).
 * default_instructor_id only affects the instructor NEW enrollments in that
 * track get by default; it does not change anyone already enrolled.
 * Idempotent.
 */
class AcademyBeginnerTracksInstructorSeeder extends Seeder
{
    public function run(): void
    {
        $amir = User::where('email', 'shanbalouchkhan@gmail.com')->first();

        if (!$amir) {
            $this->command?->warn('Skipping instructor assignment: no user found for shanbalouchkhan@gmail.com.');
            return;
        }

        AcademyStaffRole::firstOrCreate([
            'user_id' => $amir->id,
            'capability' => AcademyStaffRole::CAPABILITY_INSTRUCTOR,
        ]);
        $this->command?->info("{$amir->name}: instructor capability confirmed.");

        $trackNames = ['Digital & AI Foundations', 'YouTube & Content Automation'];

        AcademyTrack::whereIn('name', $trackNames)->get()->each(function (AcademyTrack $track) use ($amir) {
            $track->update(['default_instructor_id' => $amir->id]);
            $this->command?->info("{$track->name}: default instructor set to {$amir->name}.");
        });
    }
}
