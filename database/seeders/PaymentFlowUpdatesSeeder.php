<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * One-shot seeder to apply the recent Income / Payment Request BREAD and
 * schema updates on a server without waiting for a full `php artisan
 * migrate` deploy cycle. It simply runs the up() method of each migration
 * file listed below - identical logic, so there is no drift between what
 * this seeder does and what the migrations do.
 *
 * All of these migrations are idempotent (they set JSON field config /
 * ALTER TABLE ... MODIFY ... NULL), so running this seeder more than once,
 * or running it and later also running `php artisan migrate --force`, is
 * safe either way.
 *
 * Run with:
 *   php artisan db:seed --class=Database\\Seeders\\PaymentFlowUpdatesSeeder --force
 */
class PaymentFlowUpdatesSeeder extends Seeder
{
    /**
     * Migration files this seeder replays, in order. NOTE: does not include
     * 2026_07_17_075150_create_user_payment_logs_table.php - that one does a
     * real Schema::create() (not idempotent field-config updates like the
     * rest), so it must run via `php artisan migrate --force`, not this
     * seeder, to avoid a "table already exists" error on a second run.
     */
    private array $migrationFiles = [
        '2026_07_15_000001_simplify_income_form.php',
        '2026_07_15_000002_income_labels_order_and_permissions.php',
        '2026_07_17_000001_income_required_fields_and_labels.php',
        '2026_07_17_000002_income_conversion_rate_description.php',
        '2026_07_18_000001_grant_business_developer_payment_permissions.php',
        '2026_07_18_000002_restrict_financials_cash_menu_item.php',
        '2026_07_18_000003_project_dropdown_labels_show_id.php',
    ];

    public function run(): void
    {
        foreach ($this->migrationFiles as $file) {
            $path = database_path('migrations/' . $file);

            if (!file_exists($path)) {
                $this->command?->warn("Skipped (file not found): {$file}");
                continue;
            }

            $migration = require $path;
            $migration->up();

            $this->command?->info("Applied: {$file}");
        }

        $this->command?->info('Done. If this server also runs `php artisan migrate --force` '
            . 'later, these same migrations will run again safely (they only overwrite the '
            . 'same BREAD config / nullable-column change) and will then be correctly recorded '
            . 'in the migrations table.');
    }
}
