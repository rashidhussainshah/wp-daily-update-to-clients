<?php

namespace App\Console\Commands;

use App\Models\AcademyFeeInvoice;
use App\Models\DeveloperAcademyEnrollment;
use Illuminate\Console\Command;

/**
 * Monthly fee generation for every ACTIVE Academy enrollment - the first
 * invoice is already created at registration time
 * (AcademyRegistrationController::store), this covers every month after
 * that for as long as the enrollment stays active. Registration fee is
 * NOT re-charged here (already a one-time cost billed at signup) - only
 * the recurring monthly fee, at the track's CURRENT rate (so a later fee
 * change applies to future months without touching already-issued
 * invoices, same as any normal invoicing).
 *
 * Idempotent via the existing unique(enrollment_id, month) constraint -
 * safe to run more than once in the same month.
 */
class GenerateAcademyMonthlyInvoices extends Command
{
    protected $signature = 'academy:generate-monthly-invoices';
    protected $description = 'Generate this month\'s fee invoice for every active Academy enrollment that doesn\'t already have one';

    public function handle(): void
    {
        $month = now()->startOfMonth();
        $created = 0;

        DeveloperAcademyEnrollment::active()->with('track')->chunk(100, function ($enrollments) use ($month, &$created) {
            foreach ($enrollments as $enrollment) {
                if (!$enrollment->track) {
                    continue;
                }

                $invoice = AcademyFeeInvoice::firstOrCreate(
                    ['enrollment_id' => $enrollment->id, 'month' => $month],
                    [
                        'registration_fee_amount' => 0,
                        'monthly_fee_amount' => $enrollment->track->monthly_fee_amount,
                        'total_amount' => $enrollment->track->monthly_fee_amount,
                    ]
                );

                if ($invoice->wasRecentlyCreated) {
                    $created++;
                }
            }
        });

        $this->info("Generated {$created} invoice(s) for {$month->format('F Y')}.");
    }
}
