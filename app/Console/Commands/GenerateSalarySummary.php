<?php

namespace App\Console\Commands;

use App\Services\SalaryCalculationService;
use Illuminate\Console\Command;

class GenerateSalarySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:summary {--user_id=} {--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate salary summary for users. Use --user_id=ID for specific user, --month=Y-m for specific month (default: current month)';

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
        $userId = $this->option('user_id');
        $month = $this->option('month') ?: now()->format('Y-m');

        $this->info("Generating salary summary for {$month}...");
        $this->newLine();

        if ($userId) {
            // Generate for specific user
            $result = $this->salaryService->calculateMonthlySalary($userId, $month);

            if (isset($result['error'])) {
                $this->error($result['error']);
                return 1;
            }

            $this->displayUserSummary($result);
        } else {
            // Generate for all users
            $results = $this->salaryService->calculateAllUsersSalary($month);

            if (empty($results)) {
                $this->warn('No active contracts with monthly salary found.');
                return 0;
            }

            foreach ($results as $result) {
                if (!isset($result['error'])) {
                    $this->displayUserSummary($result);
                    $this->newLine();
                }
            }

            $this->info("Total users processed: " . count($results));
        }

        return 0;
    }

    /**
     * Display salary summary for a user
     *
     * @param array $result
     * @return void
     */
    protected function displayUserSummary($result)
    {
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("User: {$result['user']['name']} (ID: {$result['user']['id']})");
        $this->line("Email: {$result['user']['email']}");
        $this->line("Month: {$result['month']}");
        $this->newLine();

        // Contract Info
        $this->line("<fg=cyan>Contract Information:</>");
        $this->line("  Monthly Salary: {$result['contract']['currency']} {$result['contract']['monthly_salary']}");
        $this->line("  Daily Salary: {$result['contract']['currency']} {$result['contract']['daily_salary']}");
        $this->newLine();

        // Leaves Info
        $this->line("<fg=yellow>Leave Information:</>");
        $this->line("  Allowed Monthly Leaves: {$result['leaves']['allowed_monthly_leaves']} days");
        $this->line("  Total Leave Days: {$result['leaves']['total_leave_days']} days");
        $this->line("  Exceeded Leave Days: {$result['leaves']['exceeded_leave_days']} days");

        if ($result['leaves']['exceeded_leave_days'] > 0) {
            $this->line("  <fg=red>Leave Deduction: {$result['contract']['currency']} {$result['leaves']['leave_deduction']}</>");
        } else {
            $this->line("  Leave Deduction: {$result['contract']['currency']} 0.00");
        }

        if (!empty($result['leaves']['details'])) {
            $this->line("  Leave Details:");
            foreach ($result['leaves']['details'] as $leave) {
                $this->line("    - {$leave['start_date']} to {$leave['end_date']} ({$leave['days_in_month']} days) - {$leave['reason']}");
            }
        }
        $this->newLine();

        // Fines Info
        $this->line("<fg=magenta>Fine Information:</>");
        $this->line("  Total Fines: {$result['contract']['currency']} {$result['fines']['total_fines']}");
        $this->line("  Total Paid: {$result['contract']['currency']} {$result['fines']['total_paid']}");

        if ($result['fines']['unpaid_fines'] > 0) {
            $this->line("  <fg=red>Unpaid Fines: {$result['contract']['currency']} {$result['fines']['unpaid_fines']}</>");
        } else {
            $this->line("  Unpaid Fines: {$result['contract']['currency']} 0.00");
        }

        if (!empty($result['fines']['details'])) {
            $this->line("  Fine Details:");
            foreach ($result['fines']['details'] as $fine) {
                $unpaid = $fine['amount'] - $fine['paid'];
                $this->line("    - {$fine['date']}: {$result['contract']['currency']} {$fine['amount']} (Unpaid: {$unpaid}) - {$fine['reason']}");
            }
        }
        $this->newLine();

        // Summary
        $this->line("<fg=green>Salary Summary:</>");
        $this->line("  Gross Salary: {$result['contract']['currency']} {$result['summary']['gross_salary']}");
        $this->line("  Total Deductions: {$result['contract']['currency']} {$result['summary']['total_deductions']}");
        $this->line("  <fg=green;options=bold>Net Salary: {$result['contract']['currency']} {$result['summary']['net_salary']}</>");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}
