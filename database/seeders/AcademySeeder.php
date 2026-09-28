<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Runs every Academy seeder together, in the order that respects their
 * dependencies (e.g. the curriculum must exist before the GitHub-stage
 * seeder can prepend a stage to it). Every seeder it calls is itself
 * idempotent, so re-running this whole thing is always safe.
 *
 * One exception, deliberately NOT handled here: the Academy Slack webhook
 * (academy.slack_webhook_url) and the Gemini API key
 * (academy.gemini_api_key) are live secrets - they're set directly as
 * Voyager settings, never committed to a seeder file. Set those once via
 * Voyager Settings (or ask Claude to do it) before or after running this.
 *
 * Run: php artisan db:seed --class=AcademySeeder
 */
class AcademySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AcademyTracksSeeder::class,
            AcademyGithubStageAndNewTracksSeeder::class,
            AcademyStaffRolesSeeder::class,
            AcademyStaffSetupSeeder::class,
            AcademyAccountantMarketingSeeder::class,
            AcademyMarketingResourceSeeder::class,
            AcademyBeginnerTracksInstructorSeeder::class,
            AcademyInstructorRealignmentSeeder::class,
            AcademyPrinterRoleSeeder::class,
            AcademyCardManagerRoleSeeder::class,
            AcademyTestAccountsSeeder::class,
            AcademyBreadSeeder::class,
            AcademyCheckinConfigBackfillSeeder::class,
            AcademyProjectDetailsAndNewTracksSeeder::class,
            AcademyCertificateSignatorySeeder::class,
            AcademyDemoCertificatesSeeder::class,
            AcademyOverdueFeesDemoSeeder::class,
        ]);
    }
}
