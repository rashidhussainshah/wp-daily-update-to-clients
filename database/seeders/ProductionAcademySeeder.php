<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * The production-safe subset of AcademySeeder - real tracks/curriculum,
 * roles, BREAD registration, real-staff capability grants, and the one-time
 * checkin-config backfill (which only touches real students). Deliberately
 * EXCLUDES AcademyTestAccountsSeeder, AcademyDemoCertificatesSeeder, and
 * AcademyOverdueFeesDemoSeeder - those create fake accounts and fabricated
 * demo data that must never touch the live database.
 *
 * Signatory defaults (AcademyCertificateSignatorySeeder) are safe to run -
 * it only sets a Voyager setting if the key doesn't already exist, so it
 * can never clobber a value already configured in production. The Gemini
 * API key and Slack webhook are NOT seeded here (live secrets) - set those
 * once via Voyager Settings after deploying.
 *
 * Run on the production server: php artisan db:seed --class=ProductionAcademySeeder --force
 */
class ProductionAcademySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AcademyTracksSeeder::class,
            AcademyGithubStageAndNewTracksSeeder::class,
            AcademyStaffSetupSeeder::class,
            AcademyAccountantMarketingSeeder::class,
            AcademyMarketingResourceSeeder::class,
            AcademyBeginnerTracksInstructorSeeder::class,
            AcademyInstructorRealignmentSeeder::class,
            AcademyCardWorkflowRealAssignmentSeeder::class,
            AcademyPrinterRoleSeeder::class,
            AcademyCardManagerRoleSeeder::class,
            AcademyBreadSeeder::class,
            AcademyCheckinConfigBackfillSeeder::class,
            AcademyProjectDetailsAndNewTracksSeeder::class,
            AcademyCertificateSignatorySeeder::class,
        ]);
    }
}
