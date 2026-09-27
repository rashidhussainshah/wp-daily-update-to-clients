<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Production-safe setup for the HR document builder: the dedicated
 * hr-document-manager role, and the real starter letter templates
 * (Experience/Relieving/Internship Completion) plus the company-name and
 * signatory-image Voyager settings they read from. No test accounts, no
 * demo issuances.
 *
 * Run on the production server: php artisan db:seed --class=ProductionHrDocumentsSeeder --force
 */
class ProductionHrDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HrDocumentManagerRoleSeeder::class,
            DocumentTemplateSeeder::class,
        ]);
    }
}
