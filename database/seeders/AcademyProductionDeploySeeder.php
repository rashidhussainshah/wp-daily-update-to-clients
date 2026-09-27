<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * The ONE seeder to run after merging this branch to master and deploying -
 * everything built in this Academy/HR-documents effort, in the right order,
 * so the whole feature set goes live with a single command:
 *
 *   php artisan db:seed --class=AcademyProductionDeploySeeder --force
 *
 * Includes, deliberately, for now:
 * - Every real structural piece: tracks/curriculum, roles, BREAD, real-staff
 *   capability grants (Amir Sohail, Ahmad Raza, Ali Hassan, Ayub Khokhar,
 *   Rumaisha Asif, Mehtab Sain), the HR document builder + starter letter
 *   templates, and the real staff skill certificates (Ahmad Raza, Ayub,
 *   Ali Hassan, Muhammad Sadiq).
 * - The test.*@webpenter.test accounts (password Test@12345) - explicitly
 *   wanted live for now so the team can log in and test the deployed
 *   features with familiar accounts before onboarding real staff onto their
 *   own logins. Safe to stop seeding these in a later, separate run once
 *   that's no longer needed - nothing else in this chain depends on them.
 *
 * Live secrets are NOT seeded here (by design, same as ProductionAcademySeeder):
 * set academy.gemini_api_key, academy.slack_webhook_url, and an active
 * SmtpAccount (for HR document emails) once via Voyager Settings after this
 * runs - see the Certification/Academy/HR Documents setting groups.
 */
class AcademyProductionDeploySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductionAcademySeeder::class,
            ProductionHrDocumentsSeeder::class,
            AcademyTestAccountsSeeder::class,
            HrDocumentManagerTestAccountSeeder::class,
            AcademyRealStaffCertificatesSeeder::class,
        ]);
    }
}
