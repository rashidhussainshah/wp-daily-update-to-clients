<?php

namespace Database\Seeders;

use App\Models\AcademyCourse;
use App\Models\AcademyTrack;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo data so the Marketing capability has a real gallery to test with -
 * one completed (fake) student per track, each with a real, real-PDF track
 * certificate, plus a couple of course certificates. Clearly named/emailed
 * as demo data, not meant to be mistaken for real students. Idempotent.
 *
 * Run: php artisan db:seed --class=AcademyDemoCertificatesSeeder
 */
class AcademyDemoCertificatesSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['name' => 'Git & GitHub Essentials', 'description' => 'Version control fundamentals every WebPenter Academy student completes first.', 'category' => 'Foundation'],
            ['name' => 'MS Office & Digital Skills Fundamentals', 'description' => 'Typing, Word, Excel, and AI-assisted productivity basics.', 'category' => 'Foundation'],
        ];

        foreach ($courses as $course) {
            AcademyCourse::firstOrCreate(['name' => $course['name']], $course);
        }

        $demoStudents = [
            ['name' => 'Demo — Sara Khalid', 'track' => 'Python & AI/ML Engineer'],
            ['name' => 'Demo — Bilal Ahmed', 'track' => 'Full Stack Laravel/PHP Developer'],
            ['name' => 'Demo — Ayesha Noor', 'track' => 'WordPress Developer & Customization Specialist'],
            ['name' => 'Demo — Usman Tariq', 'track' => 'React / React Native Developer'],
            ['name' => 'Demo — Hina Malik', 'track' => 'Frontend Fundamentals: HTML, CSS, JavaScript & Bootstrap'],
            ['name' => 'Demo — Faizan Riaz', 'track' => 'PHP & WordPress Developer'],
            ['name' => 'Demo — Zara Iqbal', 'track' => 'Digital & AI Foundations'],
        ];

        $certificateService = app(CertificateService::class);

        foreach ($demoStudents as $demo) {
            $email = strtolower(str_replace(['Demo — ', ' '], ['demo.', '.'], $demo['name'])) . '@example.com';
            $track = AcademyTrack::where('name', $demo['track'])->first();

            if (!$track) {
                $this->command?->warn("Skipping {$demo['name']}: track '{$demo['track']}' not found.");
                continue;
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = new User(['name' => $demo['name'], 'email' => $email, 'password' => Hash::make('Test@12345')]);
                $user->role_id = (int) setting('academy.it_academy_student_role_id');
                $user->save();
            }

            $enrollment = DeveloperAcademyEnrollment::where('user_id', $user->id)->where('track_id', $track->id)->first();

            if (!$enrollment) {
                $stages = $track->stages();
                $enrollment = DeveloperAcademyEnrollment::create([
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'instructor_id' => $track->default_instructor_id,
                    'current_stage' => max(0, count($stages) - 1),
                    'status' => DeveloperAcademyEnrollment::STATUS_COMPLETED,
                    'badges_earned' => collect($stages)->map(fn ($s) => ['title' => $s['title'], 'awarded_at' => now()->subDays(rand(5, 90))->toDateString()])->all(),
                ]);
            }

            if (!$enrollment->certificate()->where('type', 'track')->exists()) {
                $certificateService->issueTrackCertificate($enrollment);
                $this->command?->info("Issued track certificate: {$demo['name']} ({$demo['track']}).");
            } else {
                $this->command?->info("Already has a track certificate: {$demo['name']}.");
            }
        }

        // A couple of course certificates too, so Marketing sees both types.
        $courseCertRecipients = [
            ['email' => 'demo.sara.khalid@example.com', 'course' => 'Git & GitHub Essentials'],
            ['email' => 'demo.bilal.ahmed@example.com', 'course' => 'MS Office & Digital Skills Fundamentals'],
            ['email' => 'demo.zara.iqbal@example.com', 'course' => 'Git & GitHub Essentials'],
        ];

        foreach ($courseCertRecipients as $entry) {
            $user = User::where('email', $entry['email'])->first();
            $course = AcademyCourse::where('name', $entry['course'])->first();

            if (!$user || !$course) {
                continue;
            }

            if (!$user->academyCertificates()->where('course_id', $course->id)->exists()) {
                $certificateService->issueCourseCertificate($user, $course->id, $course->name, $user->name, null);
                $this->command?->info("Issued course certificate: {$user->name} ({$course->name}).");
            }
        }
    }
}
