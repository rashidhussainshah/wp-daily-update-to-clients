<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Leave;
use App\Models\Fine;
use App\Services\SalaryCalculationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestSalarySystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:test {--user_id=} {--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test salary calculation system with sample data. Use --user_id=ID for specific user (default: first contract), --month=Y-m for specific month (default: current month)';

    protected $salaryService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SalaryCalculationService $salaryService)
    {
        parent::__construct();
        $this->salaryService = $salaryService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('          SALARY CALCULATION SYSTEM - TEST MODE            ');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        // Get user ID from option or use first contract
        $userId = $this->option('user_id');
        if (!$userId) {
            $contract = Contract::where('status', 'active')
                ->whereNotNull('monthly_salary')
                ->first();

            if (!$contract) {
                $this->error('No active contracts found. Creating test data...');
                $this->createTestData();
                return 0;
            }

            $userId = $contract->user_id;
            $this->info("Using first active contract (User ID: {$userId})");
        }

        // Get month from option or use current month
        $month = $this->option('month') ?: now()->format('Y-m');
        $this->info("Testing for month: {$month}");
        $this->newLine();

        // Display test data overview
        $this->displayTestDataOverview($userId, $month);
        $this->newLine();

        // Run salary calculation
        $this->info('Running salary calculation...');
        $this->newLine();

        $result = $this->salaryService->calculateMonthlySalary($userId, $month);

        if (isset($result['error'])) {
            $this->error($result['error']);
            return 1;
        }

        // Display results
        $this->displayResults($result);

        // Test API endpoints
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('                  API ENDPOINT EXAMPLES                    ');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        $baseUrl = config('app.url');
        $this->line("Test these endpoints in Postman or browser:");
        $this->newLine();
        $this->line("<fg=cyan>1. Get user salary summary:</>");
        $this->line("   GET {$baseUrl}/api/salary/user-summary?user_id={$userId}&month={$month}");
        $this->newLine();
        $this->line("<fg=cyan>2. Get all users salary summary:</>");
        $this->line("   GET {$baseUrl}/api/salary/all-users-summary?month={$month}");
        $this->newLine();
        $this->line("<fg=cyan>3. Get current month salaries:</>");
        $this->line("   GET {$baseUrl}/api/salary/current-month");
        $this->newLine();
        $this->line("<fg=cyan>4. Generate salary invoice (HTML):</>");
        $this->line("   GET {$baseUrl}/api/salary/invoice?user_id={$userId}&month={$month}");
        $this->newLine();
        $this->line("<fg=cyan>5. Get invoice data (JSON):</>");
        $this->line("   GET {$baseUrl}/api/salary/invoice-data?user_id={$userId}&month={$month}");
        $this->newLine();
        $this->line("<fg=cyan>6. Artisan command to view salary summary:</>");
        $this->line("   php artisan salary:summary --user_id={$userId} --month={$month}");
        $this->newLine();

        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('                     TEST COMPLETED                        ');
        $this->info('═══════════════════════════════════════════════════════════');

        return 0;
    }

    /**
     * Display test data overview
     */
    protected function displayTestDataOverview($userId, $month)
    {
        $this->info('Test Data Overview:');
        $this->line('───────────────────────────────────────────────────────────');

        $contract = Contract::where('user_id', $userId)->first();
        if ($contract) {
            $this->line("Contract: {$contract->title}");
            $this->line("Monthly Salary: {$contract->currency} {$contract->monthly_salary}");
        } else {
            $this->warn("No contract found for user {$userId}");
        }

        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $leaves = Leave::where('user_id', $userId)
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
            })
            ->get();

        $this->line("Leaves in month: {$leaves->count()}");
        foreach ($leaves as $leave) {
            $this->line("  - {$leave->start_date} to {$leave->end_date}: {$leave->reason}");
        }

        $fines = Fine::where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $this->line("Fines in month: {$fines->count()}");
        foreach ($fines as $fine) {
            $this->line("  - {$fine->date}: {$contract->currency} {$fine->amount} - {$fine->reason}");
        }
    }

    /**
     * Display calculation results
     */
    protected function displayResults($result)
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("                  CALCULATION RESULTS                      ");
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        $this->line("<fg=green>User Information:</>");
        $this->line("  Name: {$result['user']['name']}");
        $this->line("  Email: {$result['user']['email']}");
        $this->line("  ID: {$result['user']['id']}");
        $this->newLine();

        $this->line("<fg=cyan>Contract Details:</>");
        $this->line("  Title: {$result['contract']['title']}");
        $this->line("  Monthly Salary: {$result['contract']['currency']} {$result['contract']['monthly_salary']}");
        $this->line("  Daily Salary: {$result['contract']['currency']} {$result['contract']['daily_salary']}");
        $this->newLine();

        $this->line("<fg=yellow>Leave Summary:</>");
        $this->line("  Allowed Monthly Leaves: {$result['leaves']['allowed_monthly_leaves']} days");
        $this->line("  Total Leave Days: {$result['leaves']['total_leave_days']} days");
        $this->line("  Exceeded Leave Days: {$result['leaves']['exceeded_leave_days']} days");
        $this->line("  Leave Deduction: {$result['contract']['currency']} {$result['leaves']['leave_deduction']}");
        $this->newLine();

        $this->line("<fg=magenta>Fine Summary:</>");
        $this->line("  Total Fines: {$result['contract']['currency']} {$result['fines']['total_fines']}");
        $this->line("  Total Paid: {$result['contract']['currency']} {$result['fines']['total_paid']}");
        $this->line("  Unpaid Fines: {$result['contract']['currency']} {$result['fines']['unpaid_fines']}");
        $this->newLine();

        $this->line("<fg=green;options=bold>FINAL SALARY CALCULATION:</>");
        $this->line("  Gross Salary:      {$result['contract']['currency']} {$result['summary']['gross_salary']}");
        $this->line("  Total Deductions:  {$result['contract']['currency']} {$result['summary']['total_deductions']}");
        $this->line("  ───────────────────────────────────────");
        $this->line("  <fg=green;options=bold>NET SALARY:        {$result['contract']['currency']} {$result['summary']['net_salary']}</>");
        $this->newLine();
    }

    /**
     * Create test data if no contracts exist
     */
    protected function createTestData()
    {
        $this->warn('This feature requires existing contracts in the database.');
        $this->warn('Please add a contract through Voyager admin panel first.');
        $this->newLine();
        $this->info('Steps to create test data:');
        $this->line('1. Access Voyager admin panel');
        $this->line('2. Create a contract with monthly_salary field');
        $this->line('3. Add some leaves and fines for testing');
        $this->line('4. Run this command again');
    }
}
