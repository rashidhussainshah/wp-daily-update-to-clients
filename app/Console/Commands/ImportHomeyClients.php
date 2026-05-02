<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;

class ImportHomeyClients extends Command
{
    protected $signature = 'import:homey-clients
                            {--file= : Path to CSV (default: client_data/homey_clients.csv)}
                            {--chunk=1000 : Insert chunk size}
                            {--dry-run : Preview counts without writing to DB}';

    protected $description = 'Import 66k Homey theme client emails as users with the homey_client role';

    public function handle(): int
    {
        // Prefer the pre-processed CSV; fall back to original XLS for reference
        $file = $this->option('file')
            ?? base_path('client_data/homey_clients.csv');

        $chunkSize = (int) $this->option('chunk');
        $dryRun    = $this->option('dry-run');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            $this->line("Generate it first: python client_data/convert.py");
            return 1;
        }

        $role = Role::where('name', 'homey_client')->first();
        if (!$role) {
            $this->error('Role "homey_client" not found. Run: php artisan db:seed --class=RolesTableSeeder');
            return 1;
        }

        $this->info("Reading {$file} ...");
        $rows = $this->readCsv($file);
        $this->info("Read " . count($rows) . " rows.");

        // Load existing emails into a flip-map for O(1) lookup
        $existingEmails = DB::table('users')
            ->whereNotNull('email')
            ->pluck('email')
            ->flip()
            ->all();

        // Pre-hash ONE password for all imported users — avoids 66k bcrypt calls.
        // These are marketing contacts; they must use "Forgot Password" to set their own.
        $defaultHash = bcrypt(Str::random(32));

        $toInsert = [];
        $skipped  = 0;
        $now      = now();

        foreach ($rows as $row) {
            $email = strtolower(trim($row['email'] ?? ''));
            $name  = trim($row['name'] ?? '');

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            if (isset($existingEmails[$email])) {
                $skipped++;
                continue;
            }

            $existingEmails[$email] = true;

            $toInsert[] = [
                'name'       => $name ?: $this->nameFromEmail($email),
                'email'      => $email,
                'password'   => $defaultHash,
                'role_id'    => $role->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $total = count($toInsert);
        $this->info("Valid new records: {$total} | Skipped (invalid/duplicate): {$skipped}");

        if ($dryRun) {
            $this->warn('[Dry run] No records written.');
            return 0;
        }

        if ($total === 0) {
            $this->info('Nothing to import.');
            return 0;
        }

        $bar = $this->output->createProgressBar(ceil($total / $chunkSize));
        $bar->start();

        $inserted = 0;
        foreach (array_chunk($toInsert, $chunkSize) as $chunk) {
            // Raw DB insert bypasses the Clockify observer on User::created
            DB::table('users')->insert($chunk);
            $inserted += count($chunk);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done. Imported {$inserted} Homey clients (role_id={$role->id}).");

        return 0;
    }

    private function readCsv(string $file): array
    {
        $rows   = [];
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // skip header row

        while (($cols = fgetcsv($handle)) !== false) {
            if (count($cols) >= 2) {
                $rows[] = [
                    'name'  => $cols[0] ?? '',
                    'email' => $cols[1] ?? '',
                ];
            }
        }

        fclose($handle);
        return $rows;
    }

    private function nameFromEmail(string $email): string
    {
        $local = explode('@', $email)[0];
        return ucwords(str_replace(['.', '_', '-'], ' ', $local));
    }
}
