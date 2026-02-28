<?php

namespace App\Console\Commands;

use App\Models\Fine;
use App\Models\SalaryInvoiceLog;
use App\Models\User;
use App\Services\SalaryCalculationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

/**
 * Read-only Monthly Salary Preview Command
 *
 * Shows a breakdown of how much needs to be paid for each team member
 * without making any changes to the database.
 *
 * Usage:
 *   php artisan salary:preview                        # Preview all team members for current month
 *   php artisan salary:preview --month=2025-10        # Preview for specific month
 *   php artisan salary:preview --user_id=1            # Preview specific user
 */
class PreviewMonthlySalaries extends Command
{
    protected $signature = 'salary:preview
                            {--month= : Month in Y-m format (e.g., 2025-10)}
                            {--user_id= : Preview specific user only}';

    protected $description = 'Read-only preview of monthly salary payouts for development team members';

    protected SalaryCalculationService $salaryService;
    protected string $currentMonth;

    public function __construct(SalaryCalculationService $salaryService)
    {
        parent::__construct();
        $this->salaryService = $salaryService;
    }

    public function handle(): int
    {
        try {
            $this->currentMonth = $this->validateAndGetMonth();
            $this->displayHeader();

            $users = $this->getUsersToProcess();

            if ($users->isEmpty()) {
                $this->error('No development team members found with active contracts.');
                return self::FAILURE;
            }

            $this->info("Found {$users->count()} team member(s) to preview.");
            $this->newLine();

            $rows = [];
            $totals = [];   // keyed by currency

            foreach ($users as $user) {
                $result = $this->buildUserRow($user);
                if ($result === null) {
                    continue;
                }

                $rows[] = $result['row'];

                $cur = $result['currency'];
                $totals[$cur] = ($totals[$cur] ?? ['gross' => 0, 'deductions' => 0, 'net' => 0, 'pending_fines' => 0]);
                $totals[$cur]['gross']         += $result['gross'];
                $totals[$cur]['deductions']    += $result['deductions'];
                $totals[$cur]['net']           += $result['net'];
                $totals[$cur]['pending_fines'] += $result['pending_fines'];
            }

            // Per-user table
            $this->table(
                ['#', 'Name', 'Currency', 'Gross', 'Leave Ded.', 'Fine Ded.', 'Advance Ded.', 'Net Payable', 'Pending Fines*', 'Status'],
                $rows
            );

            $this->newLine();
            $this->line('  * Pending Fines: not yet approved for deduction — will reduce Net if confirmed during processing.');
            $this->newLine();

            $this->displayTotals($totals);

            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    // ──────────────────────────────────────────────────────────────────────────

    protected function validateAndGetMonth(): string
    {
        $month = $this->option('month') ?: now()->format('Y-m');

        try {
            Carbon::createFromFormat('Y-m', $month);
        } catch (Exception) {
            throw new Exception("Invalid month format. Please use Y-m format (e.g., 2025-10)");
        }

        return $month;
    }

    protected function getUsersToProcess()
    {
        $userId = $this->option('user_id');

        if ($userId) {
            return User::where('id', $userId)->get();
        }

        return User::where('is_development_team_member', true)
            ->whereHas('contract', function ($query) {
                $query->where('status', 'active')
                    ->whereNotNull('monthly_salary');
            })
            ->get();
    }

    /**
     * Build a single table row for a user.
     * Returns null when the salary cannot be calculated.
     *
     * @return array{row: array, currency: string, gross: float, deductions: float, net: float, pending_fines: float}|null
     */
    protected function buildUserRow(User $user): ?array
    {
        $data = $this->salaryService->calculateMonthlySalary($user->id, $this->currentMonth);

        if (isset($data['error'])) {
            $this->warn("  Skipping {$user->name}: {$data['error']}");
            return null;
        }

        $currency      = $data['contract']['currency'];
        $gross         = $data['summary']['gross_salary'];
        $leaveDeduct   = $data['leaves']['leave_deduction'];
        $fineDeduct    = $data['fines']['fines_for_deduction'];   // already-deducted fines
        $advanceDeduct = $data['advances']['total_advance'];
        $net           = $data['summary']['net_salary'];

        // Pending fines not yet confirmed for deduction
        $pendingFines = $this->getPendingFinesTotal($user->id);

        // Payment status
        $status = $this->resolveStatus($user->id, $net, $pendingFines);

        $fmt = fn(float $v) => number_format($v, 2);

        return [
            'row' => [
                $user->id,
                $user->name,
                $currency,
                $fmt($gross),
                $leaveDeduct   > 0 ? "- {$fmt($leaveDeduct)}"   : '-',
                $fineDeduct    > 0 ? "- {$fmt($fineDeduct)}"    : '-',
                $advanceDeduct > 0 ? "- {$fmt($advanceDeduct)}" : '-',
                $fmt($net),
                $pendingFines  > 0 ? "! {$fmt($pendingFines)}"  : '-',
                $status,
            ],
            'currency'      => $currency,
            'gross'         => $gross,
            'deductions'    => $data['summary']['total_deductions'],
            'net'           => $net,
            'pending_fines' => $pendingFines,
        ];
    }

    /**
     * Pending fines for this month that have NOT been marked as deducted yet.
     * These are NOT included in the salary calculation but will reduce net pay
     * if confirmed during salary:process.
     */
    protected function getPendingFinesTotal(int $userId): float
    {
        $date = Carbon::createFromFormat('Y-m', $this->currentMonth);

        return (float) Fine::where('user_id', $userId)
            ->whereBetween('date', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ])
            ->where('status', 'pending')
            ->sum('amount');
    }

    /**
     * Determine payment status label for a user.
     */
    protected function resolveStatus(int $userId, float $net, float $pendingFines): string
    {
        $log = SalaryInvoiceLog::where('user_id', $userId)
            ->where('month', $this->currentMonth)
            ->first();

        if (!$log) {
            return '<fg=yellow>Pending</>'; // not touched yet
        }

        if ($log->email_sent) {
            return '<fg=green>Paid</>'; // payment sent
        }

        return '<fg=cyan>Draft</>'; // log exists but email not sent
    }

    // ──────────────────────────────────────────────────────────────────────────

    protected function displayHeader(): void
    {
        $monthName = Carbon::createFromFormat('Y-m', $this->currentMonth)->format('F Y');

        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("          SALARY PAYOUT PREVIEW — {$monthName}");
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();
        $this->line('  <fg=yellow>READ-ONLY — no data will be changed.</> Run <fg=cyan>salary:process</> to finalize.');
        $this->newLine();
    }

    protected function displayTotals(array $totals): void
    {
        if (empty($totals)) {
            return;
        }

        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');
        $this->info('                        TOTALS BY CURRENCY');
        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');
        $this->newLine();

        foreach ($totals as $currency => $t) {
            $fmt = fn(float $v) => number_format($v, 2);

            $this->line("  <fg=cyan>{$currency}</>");
            $this->line("    Gross Salary:        {$fmt($t['gross'])}");
            $this->line("    Total Deductions:  - {$fmt($t['deductions'])}");
            $this->line("    <fg=green;options=bold>Net Payable:           {$fmt($t['net'])}</>");

            if ($t['pending_fines'] > 0) {
                $worstCase = $t['net'] - $t['pending_fines'];
                $this->line("    <fg=yellow>Pending Fines:       - {$fmt($t['pending_fines'])}</>");
                $this->line("    <fg=yellow>Net (if fines deducted): {$fmt($worstCase)}</>");
            }

            $this->newLine();
        }
    }
}
