<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Fine;
use App\Models\AdvanceSalary;
use App\Models\SalaryInvoiceLog;
use App\Services\SalaryCalculationService;
use App\Services\SalaryInvoiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalaryInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;

/**
 * Interactive Monthly Salary Processing Command
 *
 * This command processes salaries for development team members at month-end.
 * It handles leaves, fines, advance salaries, and generates invoices.
 *
 * Usage:
 *   php artisan salary:process                        # Process all team members for current month
 *   php artisan salary:process --month=2025-10        # Process for specific month
 *   php artisan salary:process --user_id=1            # Process specific user
 */
class ProcessMonthlySalaries extends Command
{
    /**
     * Command signature
     */
    protected $signature = 'salary:process {--month= : Month in Y-m format (e.g., 2025-10)} {--user_id= : Process specific user only}';

    /**
     * Command description
     */
    protected $description = 'Interactive salary processing for development team members';

    /**
     * Salary calculation service
     */
    protected $salaryService;

    /**
     * Invoice generation service
     */
    protected $invoiceService;

    /**
     * Current processing month
     */
    protected $currentMonth;

    /**
     * Constructor
     */
    public function __construct(SalaryCalculationService $salaryService, SalaryInvoiceService $invoiceService)
    {
        parent::__construct();
        $this->salaryService = $salaryService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        try {
            // Validate and set month
            $this->currentMonth = $this->validateAndGetMonth();

            // Display header
            $this->displayHeader();

            // Get users to process
            $users = $this->getUsersToProcess();

            if ($users->isEmpty()) {
                $this->error('No development team members found with active contracts.');
                return self::FAILURE;
            }

            $this->info("Found {$users->count()} team member(s) to process.");
            $this->newLine();

            // Process each user
            $processedCount = 0;
            foreach ($users as $user) {
                if ($this->processUserSalary($user)) {
                    $processedCount++;
                }
                $this->newLine();
            }

            // Display completion message
            $this->displayFooter($processedCount, $users->count());

            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return self::FAILURE;
        }
    }

    /**
     * Validate and get the processing month
     */
    protected function validateAndGetMonth(): string
    {
        $month = $this->option('month') ?: now()->format('Y-m');

        // Validate month format
        try {
            Carbon::createFromFormat('Y-m', $month);
        } catch (Exception $e) {
            throw new Exception("Invalid month format. Please use Y-m format (e.g., 2025-10)");
        }

        return $month;
    }

    /**
     * Get users to process based on options
     */
    protected function getUsersToProcess()
    {
        $userId = $this->option('user_id');

        if ($userId) {
            // Process specific user
            return User::where('id', $userId)->get();
        }

        // Process all development team members with active contracts
        return User::where('is_development_team_member', true)
            ->whereHas('contract', function ($query) {
                $query->where('status', 'active')
                    ->whereNotNull('monthly_salary');
            })
            ->get();
    }

    /**
     * Process salary for a single user
     *
     * @param User $user
     * @return bool Success status
     */
    protected function processUserSalary(User $user): bool
    {
        try {
            // Display user header
            $this->displayUserHeader($user);

            // Check if salary has already been processed and payment sent
            $existingLog = $this->checkExistingPayment($user->id);
            if ($existingLog) {
                $this->error("⚠ DUPLICATE PAYMENT PREVENTION");
                $this->error("Salary for {$user->name} has already been processed and payment sent for {$this->currentMonth}.");
                $this->line("  Invoice Number: {$existingLog->invoice_number}");
                $this->line("  Payment Sent At: {$existingLog->email_sent_at}");
                $this->line("  Net Salary: {$existingLog->currency} " . number_format($existingLog->net_salary, 2));
                $this->error("Cannot process salary twice for the same month to prevent duplicate payments.");
                $this->newLine();
                return false;
            }

            // Calculate initial salary
            $data = $this->calculateSalary($user->id);
            if (!$data) {
                return false;
            }

            // Display salary summary
            $this->displaySalarySummary($data);

            // Interactive processing
            $this->handleFinesInteraction($user->id, $data);
            $this->handleAdvanceInteraction($user->id, $data);

            // Recalculate after changes
            $data = $this->calculateSalary($user->id);
            if (!$data) {
                return false;
            }

            // Display final summary
            $this->displayFinalSummarySection($data);

            // Finalize if confirmed
            if ($this->confirmFinalization($user)) {
                $this->finalizeSalary($user, $data);
                return true;
            }

            $this->warn("Skipped salary finalization for {$user->name}");
            return false;

        } catch (Exception $e) {
            $this->error("Error processing {$user->name}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if salary has already been processed and payment sent for this month
     *
     * @param int $userId
     * @return SalaryInvoiceLog|null Returns the log if payment already sent, null otherwise
     */
    protected function checkExistingPayment(int $userId): ?SalaryInvoiceLog
    {
        return SalaryInvoiceLog::where('user_id', $userId)
            ->where('month', $this->currentMonth)
            ->where('email_sent', true)
            ->whereNotNull('email_sent_at')
            ->first();
    }

    /**
     * Calculate salary for a user
     *
     * @param int $userId
     * @return array|null Salary data or null on error
     */
    protected function calculateSalary(int $userId): ?array
    {
        $data = $this->salaryService->calculateMonthlySalary($userId, $this->currentMonth);

        if (isset($data['error'])) {
            $this->error($data['error']);
            return null;
        }

        return $data;
    }

    /**
     * Handle fines interaction - Ask ONCE to deduct all fines from this month
     */
    protected function handleFinesInteraction(int $userId, array $data): void
    {
        // Get pending fines for this month
        $date = Carbon::createFromFormat('Y-m', $this->currentMonth);
        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();

        $pendingFines = Fine::where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'pending')
            ->get();

        if ($pendingFines->isEmpty()) {
            return;
        }

        $currency = $data['contract']['currency'];
        $totalFines = $pendingFines->sum('amount');

        $this->newLine();
        $this->warn("Found {$pendingFines->count()} fine(s) for this month:");
        foreach ($pendingFines as $fine) {
            $this->line("  - {$fine->date}: {$currency} {$fine->amount} - {$fine->reason}");
        }
        $this->line("  Total: {$currency} " . number_format($totalFines, 2));

        if ($this->confirm("\nDeduct these fines from this month's salary?", true)) {
            foreach ($pendingFines as $fine) {
                $fine->status = 'deducted';
                $fine->paid = $fine->amount;
                $fine->paid_at = now();
                $fine->save();
            }
            $this->info("✓ All fines marked as deducted");
        }
    }

    /**
     * Handle advance salary interaction
     */
    protected function handleAdvanceInteraction(int $userId, array $data): void
    {
        $pendingAdvances = $this->getPendingAdvances($userId);

        if ($pendingAdvances->isEmpty()) {
            return;
        }

        $this->warn("\n{$pendingAdvances->count()} pending advance salary request(s) found:");
        $currency = $data['contract']['currency'];

        foreach ($pendingAdvances as $advance) {
            $this->line("  - {$currency} {$advance->amount} - {$advance->reason} (Created: {$advance->created_at})");

            if ($this->confirm('Approve and deduct this advance?', false)) {
                $this->approveAdvance($advance);
            }
        }
    }

    /**
     * Get pending advance salaries for the specified month
     */
    protected function getPendingAdvances(int $userId)
    {
        // Parse month to get date range
        $date = Carbon::createFromFormat('Y-m', $this->currentMonth);
        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();

        // Filter by date range since month column stores complete dates
        return AdvanceSalary::where('user_id', $userId)
            ->whereBetween('month', [$monthStart, $monthEnd])
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Approve an advance salary
     */
    protected function approveAdvance(AdvanceSalary $advance): void
    {
        try {
            $advance->status = 'approved';
            $advance->save();
            $this->info('✓ Advance approved');
        } catch (Exception $e) {
            $this->error("Failed to approve advance: " . $e->getMessage());
        }
    }

    /**
     * Confirm finalization with user - shows bank details
     */
    protected function confirmFinalization(User $user): bool
    {
        $contract = $user->contract;

        $this->newLine();
        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');
        $this->info('                   PAYMENT CONFIRMATION');
        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');
        $this->newLine();

        if ($contract && $contract->bank_detail) {
            $this->line('<fg=cyan>Bank Account Details:</>');
            $this->line($contract->bank_detail);
            $this->newLine();
        } else {
            $this->warn('⚠ No bank details found in contract');
            $this->newLine();
        }

        return $this->confirm("Send salary to this account?", true);
    }

    /**
     * Finalize salary processing - Automatically generates, saves, emails, and logs
     */
    protected function finalizeSalary(User $user, array $data): void
    {
        try {
            // Mark advances as deducted
            if ($data['advances']['total_advance'] > 0) {
                $this->markAdvancesAsDeducted($user->id);
            }

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($user->id, $this->currentMonth);

            // Generate and save invoice
            $invoicePath = $this->generateAndSaveInvoice($user->id, $invoiceNumber);

            // Generate and save PDF
            $pdfPath = $this->generateAndSavePDF($user->id, $invoiceNumber);

            // Send email
            $emailSent = $this->sendInvoiceEmail($user, $user->id, $pdfPath);

            // Create salary invoice log
            $this->createSalaryLog($user, $data, $invoiceNumber, $invoicePath, $pdfPath, $emailSent);

            $this->info("✓ Salary processing completed for {$user->name}");
            $this->info("✓ Invoice generated and saved");
            $this->info("✓ Email sent to {$user->email}");
            $this->info("✓ Transaction logged in system");

        } catch (Exception $e) {
            $this->error("Failed to finalize salary: " . $e->getMessage());
        }
    }

    /**
     * Mark advances as deducted for the specified month
     */
    protected function markAdvancesAsDeducted(int $userId): void
    {
        try {
            // Parse month to get date range
            $date = Carbon::createFromFormat('Y-m', $this->currentMonth);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            // Update advances using date range since month column stores complete dates
            AdvanceSalary::where('user_id', $userId)
                ->whereBetween('month', [$monthStart, $monthEnd])
                ->whereIn('status', ['pending', 'approved', 'paid'])
                ->update([
                    'status' => 'deducted',
                    'deducted_date' => now(),
                ]);
            $this->info('✓ Advance salary marked as deducted');
        } catch (Exception $e) {
            $this->error("Failed to mark advances as deducted: " . $e->getMessage());
        }
    }

    /**
     * Generate invoice number
     */
    protected function generateInvoiceNumber(int $userId, string $month): string
    {
        return "SAL-{$userId}-" . str_replace('-', '', $month);
    }

    /**
     * Generate and save invoice HTML
     */
    protected function generateAndSaveInvoice(int $userId, string $invoiceNumber): string
    {
        $html = $this->invoiceService->generateInvoiceHtml($userId, $this->currentMonth);

        $filename = "{$invoiceNumber}.html";
        $dir = storage_path("app/invoices/{$this->currentMonth}");

        // Create directory if it doesn't exist
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fullPath = "{$dir}/{$filename}";
        file_put_contents($fullPath, $html);

        return "invoices/{$this->currentMonth}/{$filename}";
    }

    /**
     * Generate and save PDF
     */
    protected function generateAndSavePDF(int $userId, string $invoiceNumber): string
    {
        $html = $this->invoiceService->generateInvoiceHtml($userId, $this->currentMonth);
        $pdf = Pdf::loadHTML($html)->setPaper('a4');

        $filename = "{$invoiceNumber}.pdf";
        $dir = storage_path("app/invoices/{$this->currentMonth}");

        // Create directory if needed
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fullPath = "{$dir}/{$filename}";
        $pdf->save($fullPath);

        return "invoices/{$this->currentMonth}/{$filename}";
    }

    /**
     * Send invoice via email with PDF attachment
     */
    protected function sendInvoiceEmail(User $user, int $userId, string $pdfPath): bool
    {
        try {
            // Get salary data
            $data = $this->calculateSalary($userId);
            if (!$data) {
                return false;
            }

            $fullPdfPath = storage_path("app/{$pdfPath}");

            // Send email
            Mail::to($user->email)->send(new SalaryInvoiceMail($data, $fullPdfPath));

            return true;
        } catch (Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create salary invoice log
     */
    protected function createSalaryLog(User $user, array $data, string $invoiceNumber, string $invoicePath, string $pdfPath, bool $emailSent): void
    {
        try {
            // Check if log already exists for this user and month
            $existingLog = SalaryInvoiceLog::where('user_id', $user->id)
                ->where('month', $this->currentMonth)
                ->first();

            if ($existingLog) {
                // Update existing log
                $existingLog->update([
                    'gross_salary' => $data['summary']['gross_salary'],
                    'total_deductions' => $data['summary']['total_deductions'],
                    'net_salary' => $data['summary']['net_salary'],
                    'currency' => $data['contract']['currency'],
                    'invoice_path' => $invoicePath,
                    'pdf_path' => $pdfPath,
                    'email_sent' => $emailSent,
                    'email_sent_at' => $emailSent ? now() : null,
                ]);
                $this->info('✓ Salary log updated');
            } else {
                // Create new log
                SalaryInvoiceLog::create([
                    'user_id' => $user->id,
                    'month' => $this->currentMonth,
                    'invoice_number' => $invoiceNumber,
                    'gross_salary' => $data['summary']['gross_salary'],
                    'total_deductions' => $data['summary']['total_deductions'],
                    'net_salary' => $data['summary']['net_salary'],
                    'currency' => $data['contract']['currency'],
                    'invoice_path' => $invoicePath,
                    'pdf_path' => $pdfPath,
                    'email_sent' => $emailSent,
                    'email_sent_at' => $emailSent ? now() : null,
                ]);
                $this->info('✓ Salary log created');
            }
        } catch (Exception $e) {
            $this->error("Failed to create salary log: " . $e->getMessage());
        }
    }

    // ==================== Display Methods ====================

    /**
     * Display command header
     */
    protected function displayHeader(): void
    {
        $monthName = Carbon::createFromFormat('Y-m', $this->currentMonth)->format('F Y');

        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("           MONTHLY SALARY PROCESSING - {$monthName}");
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();
    }

    /**
     * Display user processing header
     */
    protected function displayUserHeader(User $user): void
    {
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("Processing: {$user->name} (ID: {$user->id})");
        $this->line("Email: {$user->email}");
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();
    }

    /**
     * Display salary summary
     */
    protected function displaySalarySummary(array $data): void
    {
        $currency = $data['contract']['currency'];

        // Contract information
        $this->line('<fg=cyan>Contract Information:</>');
        $this->line("  Monthly Salary: {$currency} " . number_format($data['contract']['monthly_salary'], 2));
        $this->line("  Daily Salary: {$currency} " . number_format($data['contract']['daily_salary'], 2));
        $this->newLine();

        // Leaves
        if ($data['leaves']['total_leave_days'] > 0) {
            $this->displayLeaveSummary($data, $currency);
        }

        // Fines
        if ($data['fines']['fines_for_deduction'] > 0) {
            $this->displayFineSummary($data, $currency);
        }

        // Advances
        if ($data['advances']['total_advance'] > 0) {
            $this->displayAdvanceSummary($data, $currency);
        }
    }

    /**
     * Display leave summary
     */
    protected function displayLeaveSummary(array $data, string $currency): void
    {
        $this->line('<fg=yellow>Leaves:</>');
        $this->line("  Total: {$data['leaves']['total_leave_days']} days | Exceeded: {$data['leaves']['exceeded_leave_days']} days");

        if ($data['leaves']['exceeded_leave_days'] > 0) {
            $this->line("  Adjustment: {$currency} " . number_format($data['leaves']['leave_deduction'], 2));
        }

        $this->newLine();
    }

    /**
     * Display fine summary (deducted fines only)
     */
    protected function displayFineSummary(array $data, string $currency): void
    {
        if ($data['fines']['fines_for_deduction'] <= 0) {
            return;
        }

        $this->line('<fg=red>Fines to be Deducted:</>');
        $this->line("  Total: {$currency} " . number_format($data['fines']['fines_for_deduction'], 2));

        foreach ($data['fines']['details'] as $fine) {
            $this->line("    - {$fine['date']}: {$currency} {$fine['amount']} ({$fine['reason']})");
        }

        $this->newLine();
    }

    /**
     * Display advance salary summary
     */
    protected function displayAdvanceSummary(array $data, string $currency): void
    {
        $this->line('<fg=magenta>Advance Salary:</>');
        $this->line("  Total: {$currency} " . number_format($data['advances']['total_advance'], 2));

        foreach ($data['advances']['details'] as $advance) {
            $this->line("    - {$advance['created_at']}: {$currency} {$advance['amount']} ({$advance['reason']})");
        }

        $this->newLine();
    }

    /**
     * Display final summary section
     */
    protected function displayFinalSummarySection(array $data): void
    {
        $this->newLine();
        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');
        $this->info('                    FINAL SUMMARY');
        $this->line('<fg=yellow>═══════════════════════════════════════════════════════════</>');

        $this->displayFinalSummary($data);
    }

    /**
     * Display final salary calculation
     */
    protected function displayFinalSummary(array $data): void
    {
        $currency = $data['contract']['currency'];

        $this->line("Gross Salary:        {$currency} " . number_format($data['summary']['gross_salary'], 2));

        if ($data['fines']['fines_for_deduction'] > 0) {
            $this->line("Fines:               - {$currency} " . number_format($data['fines']['fines_for_deduction'], 2));
        }

        $this->line('─────────────────────────────────────────────────────────');
        $this->line("<fg=green;options=bold>NET SALARY:          {$currency} " . number_format($data['summary']['net_salary'], 2) . "</>");
        $this->newLine();

        // Show additional deductions after Net Salary
        if ($data['leaves']['leave_deduction'] > 0) {
            $days = $data['leaves']['exceeded_leave_days'];
            $this->line("Extra Leave Days ({$days} days): - {$currency} " . number_format($data['leaves']['leave_deduction'], 2));
        }

        if ($data['advances']['total_advance'] > 0) {
            $this->line("Advance Salary:      - {$currency} " . number_format($data['advances']['total_advance'], 2));
        }

        // Show final payable amount if there are additional deductions
        if ($data['leaves']['leave_deduction'] > 0 || $data['advances']['total_advance'] > 0) {
            $this->line('─────────────────────────────────────────────────────────');
            $this->line("<fg=cyan;options=bold>FINAL PAYABLE:       {$currency} " . number_format($data['summary']['final_payable'], 2) . "</>");
        }
    }

    /**
     * Display completion footer
     */
    protected function displayFooter(int $processed, int $total): void
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("     PROCESSING COMPLETED: {$processed}/{$total} SUCCESSFUL");
        $this->info('═══════════════════════════════════════════════════════════');
    }
}
