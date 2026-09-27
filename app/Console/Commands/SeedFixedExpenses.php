<?php

namespace App\Console\Commands;

use App\Models\MonthlyExpense;
use Illuminate\Console\Command;

class SeedFixedExpenses extends Command
{
    protected $signature = 'expenses:seed-fixed {--month=}';

    protected $description = 'Auto-add this month\'s fixed expenses (rent, internet, etc.) — same as the "Auto-fill Fixed" button';

    public function handle(): int
    {
        $month = $this->option('month') ?: now()->format('Y-m');
        $added = MonthlyExpense::seedFixedDefaultsForMonth($month);

        $this->info("{$added} fixed expense(s) added for {$month}.");

        return self::SUCCESS;
    }
}
