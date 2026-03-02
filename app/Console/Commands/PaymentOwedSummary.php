<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class PaymentOwedSummary extends Command
{
    protected $signature = 'payment:owed-summary
                            {--month= : Filter by month, e.g. 2026-02. Omit to see all pending grouped by month.}';

    protected $description = 'Monthly budget: show how much is owed to each person (developers + BD + partners)';

    const SADIQ_EMAIL        = 'sadiq@webpenter.com';
    const WAQAR_EMAIL        = 'waqar@webpenter.com';
    const PARTNER_PERCENTAGE = 0.375; // 37.5%

    public function handle(): int
    {
        $month = $this->option('month');

        $query = UserPayment::with('developer')->approved()->notPaid();

        if ($month) {
            $query->whereYear('created_at', substr($month, 0, 4))
                  ->whereMonth('created_at', substr($month, 5, 2));
        }

        $payments = $query->orderBy('created_at')->get();

        if ($payments->isEmpty()) {
            $this->warn($month
                ? "No approved unpaid payments found for {$month}."
                : 'No approved unpaid payments found.');
            return 0;
        }

        $this->newLine();

        if ($month) {
            $this->printReport($payments, $month);
        } else {
            // Show month-by-month breakdown first, then a cumulative total.
            $byMonth = $payments->groupBy(fn($p) => substr($p->created_at, 0, 7));

            foreach ($byMonth as $m => $monthPayments) {
                $this->printReport($monthPayments, $m);
                $this->newLine();
            }

            if ($byMonth->count() > 1) {
                $this->printReport($payments, 'ALL PENDING');
            }
        }

        return 0;
    }

    private function printReport(Collection $payments, string $label): void
    {
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line("<fg=cyan;options=bold>  BUDGET SUMMARY — {$label}</>");
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        // ── 1. Per-developer breakdown ────────────────────────────────────────
        $this->line('<fg=yellow;options=bold>Developers & BDs (payable from DB):</>');
        $this->newLine();

        $rows       = [];
        $grandTotal = 0;

        foreach ($payments->groupBy('developer_id') as $devPayments) {
            $developer    = $devPayments->first()->developer;
            $name         = $developer ? $developer->name  : 'Unknown';
            $email        = $developer ? $developer->email : '—';
            $totalPayable = $devPayments->sum('payable');
            $isAuto       = $devPayments->first()->generated_by_system;

            $grandTotal += $totalPayable;
            $rows[]      = [
                $name,
                $email,
                $isAuto ? 'BD (auto)' : 'Developer',
                $devPayments->count(),
                number_format($totalPayable, 2),
            ];
        }

        $this->table(
            ['Name', 'Email', 'Type', 'Records', 'Payable (PKR)'],
            $rows
        );

        $this->line("<fg=green>  Subtotal (DB payables): PKR " . number_format($grandTotal, 2) . "</>");
        $this->newLine();

        // ── 2. Partner share ──────────────────────────────────────────────────
        $mainPayments = $payments->where('generated_by_system', '!=', true)->values();

        $grossPkr = $mainPayments->reduce(function (float $carry, UserPayment $p): float {
            return $carry + ((float) $p->total_earning * (float) $p->currency_current_rate);
        }, 0.0);

        $sadiqShare = $grossPkr * self::PARTNER_PERCENTAGE;
        $waqarShare = $grossPkr * self::PARTNER_PERCENTAGE;

        $this->line('<fg=yellow;options=bold>Partners (37.5% of gross PKR):</>');
        $this->line("  Base = total_earning × currency_rate across {$mainPayments->count()} record(s)");
        $this->line("  Gross PKR base: <fg=white;options=bold>PKR " . number_format($grossPkr, 2) . "</>");
        $this->newLine();

        $sadiq = User::where('email', self::SADIQ_EMAIL)->first();
        $waqar = User::where('email', self::WAQAR_EMAIL)->first();

        $this->table(
            ['Partner', 'Email', '%', 'Owed (PKR)'],
            [
                [$sadiq ? $sadiq->name : 'Sadiq', self::SADIQ_EMAIL, '37.5%', number_format($sadiqShare, 2)],
                [$waqar ? $waqar->name : 'Waqar', self::WAQAR_EMAIL, '37.5%', number_format($waqarShare, 2)],
            ]
        );

        // ── 3. Grand total ────────────────────────────────────────────────────
        $totalOwed = $grandTotal + $sadiqShare + $waqarShare;

        $this->line('<fg=green;options=bold>TOTAL TO PAY THIS BUDGET:</>');
        $this->newLine();

        $this->table(
            ['Item', 'PKR'],
            [
                ['Developer & BD payables (DB)',  number_format($grandTotal, 2)],
                ['Sadiq — partner 37.5%',         number_format($sadiqShare, 2)],
                ['Waqar — partner 37.5%',         number_format($waqarShare, 2)],
                ['──────────────────────────────', '──────────────'],
                ['GRAND TOTAL',                   number_format($totalOwed, 2)],
            ]
        );
    }
}
