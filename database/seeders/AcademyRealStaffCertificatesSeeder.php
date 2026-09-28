<?php

namespace Database\Seeders;

use App\Models\AcademyCertificate;
use App\Models\AcademyCourse;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Replaces the placeholder "Demo - X" / "Test Student" certificates with
 * real skill/achievement certificates for actual WebPenter staff, so
 * Marketing has genuine content to post rather than fake names. Each
 * person's certificates match their real, current skill set (see
 * AcademyInstructorRealignmentSeeder for the same mapping). Idempotent and
 * production-safe: only ever deletes certificates belonging to
 * @example.com/@webpenter.test demo/test accounts, never a real
 * student/staff certificate, and skips re-issuing a certificate a person
 * already has.
 *
 * Run: php artisan db:seed --class=AcademyRealStaffCertificatesSeeder
 */
class AcademyRealStaffCertificatesSeeder extends Seeder
{
    public function run(): void
    {
        $this->wipeDemoCertificates();

        $courses = $this->ensureCourses();
        $service = app(CertificateService::class);

        $ahmad = User::where('email', 'ahmadraza4119@gmail.com')->firstOrFail();
        $ayub = User::where('email', 'ayubkhokhar786@gmail.com')->firstOrFail();
        $ali = User::where('email', 'alihasanwebpenter@gmail.com')->firstOrFail();
        $sadiq = User::where('email', 'sadiq@webpenter.com')->firstOrFail();

        // Ahmad Raza - PHP/Laravel/WordPress/React/React Native/Python, plus
        // a named achievement for the Booking & Rental app he actually
        // shipped end-to-end. Dates spread across 2025-2026 - these are
        // already-expert staff being certified on existing skills, not a
        // tracked training timeline, so exact dates don't matter; varied,
        // plausible ones do (so every certificate doesn't look identical
        // when Marketing posts them).
        $this->issue($service, $ahmad, $courses['php_wordpress'], AcademyCertificate::DESIGN_CLASSIC, null, Carbon::parse('2025-03-10'));
        $this->issue($service, $ahmad, $courses['laravel_fullstack'], AcademyCertificate::DESIGN_LINKEDIN, null, Carbon::parse('2025-07-22'));
        $this->issue($service, $ahmad, $courses['react_native'], AcademyCertificate::DESIGN_UDEMY, null, Carbon::parse('2025-11-14'));
        $this->issue($service, $ahmad, $courses['python_ai_ml'], AcademyCertificate::DESIGN_CLASSIC, null, Carbon::parse('2026-02-05'));
        $this->issue(
            $service,
            $ahmad,
            $courses['booking_rental_achievement'],
            AcademyCertificate::DESIGN_LINKEDIN,
            'Built and shipped the Booking & Rental mobile app end-to-end - now live on the Play Store: '
                . 'https://play.google.com/store/apps/details?id=com.webpenter.googlesignin&hl=en',
            Carbon::parse('2026-06-18')
        );

        // Ayub Khokhar & Ali Hassan - Business Development.
        $this->issue($service, $ayub, $courses['business_development'], AcademyCertificate::DESIGN_UDEMY, null, Carbon::parse('2025-04-08'));
        $this->issue($service, $ali, $courses['business_development'], AcademyCertificate::DESIGN_CLASSIC, null, Carbon::parse('2025-05-20'));

        // Ali Hassan - AI MERN Stack Development.
        $this->issue($service, $ali, $courses['ai_mern_stack'], AcademyCertificate::DESIGN_LINKEDIN, null, Carbon::parse('2026-01-12'));

        // Muhammad Sadiq - AI fundamentals + PHP/WordPress.
        $this->issue($service, $sadiq, $courses['ai_fundamentals'], AcademyCertificate::DESIGN_CLASSIC, null, Carbon::parse('2025-08-27'));
        $this->issue($service, $sadiq, $courses['php_wordpress'], AcademyCertificate::DESIGN_UDEMY, null, Carbon::parse('2026-03-15'));

        $this->command?->info('Real staff certificates issued.');
    }

    /**
     * Scoped to ONLY the fake demo/test certificates this session created
     * for local testing (recipients on the @example.com or @webpenter.test
     * domains - the two domains used exclusively for demo/test accounts
     * throughout this whole effort, never for a real person). Deliberately
     * does NOT touch any certificate belonging to a real student/staff
     * email - production may already have real, live-issued certificates
     * from the Academy program predating this feature, and those must
     * never be deleted.
     */
    protected function wipeDemoCertificates(): void
    {
        $demoCertificates = AcademyCertificate::whereHas('user', function ($q) {
            $q->where('email', 'like', '%@example.com')
                ->orWhere('email', 'like', '%@webpenter.test');
        })->with('user:id,email')->get(['id', 'user_id', 'pdf_path']);

        $demoCertificates->each(function ($cert) {
            if ($cert->pdf_path) {
                Storage::disk('public')->delete($cert->pdf_path);
            }
            $cert->delete();
        });

        $this->command?->info("Deleted {$demoCertificates->count()} demo/test certificate(s) (@example.com / @webpenter.test recipients only - real certificates untouched).");
    }

    /** @return array<string, AcademyCourse> */
    protected function ensureCourses(): array
    {
        $definitions = [
            'php_wordpress' => 'PHP & WordPress Developer',
            'laravel_fullstack' => 'Laravel & Full Stack Development',
            'react_native' => 'React & React Native Development',
            'python_ai_ml' => 'Python & AI/ML Engineering',
            'business_development' => 'Business Development & Client Acquisition',
            'ai_mern_stack' => 'AI-Powered MERN Stack Development',
            'ai_fundamentals' => 'Artificial Intelligence (AI) Fundamentals',
            'booking_rental_achievement' => 'Booking & Rental Mobile App - End-to-End Delivery',
        ];

        $courses = [];
        foreach ($definitions as $key => $name) {
            $courses[$key] = AcademyCourse::firstOrCreate(['name' => $name], ['category' => 'Staff Skill Certificate']);
        }

        return $courses;
    }

    /**
     * Guards against issuing the same person the same certificate twice if
     * this seeder is ever re-run (e.g. after an unrelated failure earlier
     * in the same deploy chain) - checked by user + course, since that pair
     * is exactly what would otherwise duplicate.
     */
    protected function issue(CertificateService $service, User $user, AcademyCourse $course, string $design, ?string $achievementNote = null, ?Carbon $issuedAt = null): void
    {
        $alreadyIssued = AcademyCertificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyIssued) {
            $this->command?->info("Already has \"{$course->name}\": {$user->name} - skipped.");
            return;
        }

        $service->issueCourseCertificate($user, $course->id, $course->name, $user->name, null, $design, $achievementNote, $issuedAt, true);
        $this->command?->info("Issued \"{$course->name}\" to {$user->name}.");
    }
}
