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
        $webhook = setting('academy.slack_webhook_url');

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
