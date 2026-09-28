<?php

namespace Database\Seeders;

use App\Models\CheckinConfiguration;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Backfills CheckinConfiguration for students who registered before
 * AcademyRegistrationController started creating this automatically -
 * without this row, CheckinController::storeCheckin/storeCheckout reject
 * them with "No Slack configuration found for the user." Idempotent:
 * firstOrCreate per student.
 *
 * Run: php artisan db:seed --class=AcademyCheckinConfigBackfillSeeder
 */
class AcademyCheckinConfigBackfillSeeder extends Seeder
{
    public function run(): void
    {
        // `slack_webhook_url` is NOT NULL on this table - on an environment
        // where the Academy Slack webhook hasn't been configured yet (e.g.
        // right after a fresh deploy, before the secret is set in Voyager
        // Settings), setting() returns null and the insert would violate
        // that constraint. Falling back to '' keeps the backfill from
        // crashing - CheckinController only checks whether a
        // CheckinConfiguration row exists at all (not whether the URL is
        // non-empty), so check-in/checkout still work; sendTxtToSlack()
        // already catches a failed post to an empty URL and just logs it
        // instead of blocking the student.
        $webhook = setting('academy.slack_webhook_url') ?: '';

        User::onlyItAcademyStudent()->with('academyEnrollments.track')->get()->each(function (User $student) use ($webhook) {
            $track = $student->academyEnrollments->first()?->track;

            $config = CheckinConfiguration::firstOrCreate(
                ['developer_id' => $student->id],
                [
                    'slack_webhook_url' => $webhook,
                    'designation' => $track?->designation ?? $track?->name ?? 'IT Academy Student',
                ]
            );

            $this->command?->info(($config->wasRecentlyCreated ? 'Created' : 'Already had').": CheckinConfiguration for {$student->email}");
        });
    }
}
