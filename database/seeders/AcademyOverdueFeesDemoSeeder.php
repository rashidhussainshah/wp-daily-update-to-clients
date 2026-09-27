<?php

namespace Database\Seeders;

use App\Models\AcademyFeeInvoice;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Backdated, still-pending invoices for real active students - so the
 * accountant screens have a genuine "this student is behind on fees"
 * scenario to test, not just the current month. Idempotent (the existing
 * unique(enrollment_id, month) constraint prevents duplicates).
 *
 * Run: php artisan db:seed --class=AcademyOverdueFeesDemoSeeder
 */
class AcademyOverdueFeesDemoSeeder extends Seeder
{
    public function run(): void
    {
        $targets = ['abdulrehman@webpenter.com', 'test.student@webpenter.test'];

        foreach ($targets as $email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->command?->warn("Skipping {$email}: not found.");
                continue;
            }

            $enrollment = $user->academyEnrollments()->active()->with('track')->first();

            if (!$enrollment) {
                $this->command?->warn("Skipping {$email}: no active enrollment.");
                continue;
            }

            $lastMonth = now()->subMonthNoOverflow()->startOfMonth();

            $invoice = AcademyFeeInvoice::firstOrCreate(
                ['enrollment_id' => $enrollment->id, 'month' => $lastMonth],
                [
                    'registration_fee_amount' => 0,
                    'monthly_fee_amount' => $enrollment->track->monthly_fee_amount,
                    'total_amount' => $enrollment->track->monthly_fee_amount,
                    'status' => AcademyFeeInvoice::STATUS_OVERDUE,
                ]
            );

            $this->command?->info(
                ($invoice->wasRecentlyCreated ? 'Created' : 'Already had')
                . " an overdue {$lastMonth->format('F Y')} invoice for {$user->name} (Rs. {$invoice->total_amount})."
            );
        }
    }
}
