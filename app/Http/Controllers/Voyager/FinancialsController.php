<?php

namespace App\Http\Controllers\Voyager;

use App\Models\BankBalance;
use App\Models\BdMonthlyTarget;
use App\Models\CashTransaction;
use App\Models\Domain;
use App\Models\Fine;
use App\Models\Income;
use App\Models\MonthlyExpense;
use App\Models\SalaryInvoiceLog;
use App\Models\User;
use App\Models\UserPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class FinancialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('financials.access');
    }

    // ── P&L Dashboard ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        [$from, $to, $label] = $this->parsePeriod($request);

        $data = $this->buildPnl($from, $to);

        $domains        = Domain::orderBy('expires_on')->get();
        $bdUsers        = User::whereIn('email', ['ayubkhokhar786@gmail.com', 'alihasanwebpenter@gmail.com'])->get();
        $bdTargets      = BdMonthlyTarget::whereBetween('month', [$from->format('Y-m'), $to->format('Y-m')])->get()->keyBy(fn($t) => $t->user_id . '_' . $t->month);

        return view('vendor.voyager.financials.index', compact(
            'data', 'domains', 'bdUsers', 'bdTargets', 'from', 'to', 'label'
        ));
    }

    // ── Mobile quick-add expense form ──────────────────────────────────────────
    public function quickExpense()
    {
        return view('vendor.voyager.financials.quick-expense');
    }

    // ── Charts & Overview page ────────────────────────────────────────────────
    public function charts(Request $request)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $chartData = $months->map(function ($month) {
            $from = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $to   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $incomeUsd = Income::whereNull('deleted_at')
                ->whereBetween('transaction_date', [$from, $to])
                ->where('amount_in', 'usd')->sum('amount');

            $incomePkr = Income::whereNull('deleted_at')
                ->whereBetween('transaction_date', [$from, $to])
                ->where('amount_in', 'pkr')->sum('amount');

            $rate = $this->avgConversionRate($from, $to);
            $totalIncomePkr = ($incomeUsd * $rate) + $incomePkr;

            $partnerPkr = UserPayment::whereNull('deleted_at')
                ->where('generated_by_system', false)
                ->whereBetween('created_at', [$from, $to])
                ->sum('payable');

            $bdPkr = UserPayment::whereNull('deleted_at')
                ->where('generated_by_system', true)
                ->whereBetween('created_at', [$from, $to])
                ->sum('payable');

            $salaryPkr = SalaryInvoiceLog::where('month', $month)->sum('net_salary');

            $expensesPkr = MonthlyExpense::where('month', $month)->sum('amount_pkr');

            $totalOut = $partnerPkr + $bdPkr + $salaryPkr + $expensesPkr;
            $saving   = $totalIncomePkr - $totalOut;

            return [
                'month'       => $month,
                'label'       => Carbon::createFromFormat('Y-m', $month)->format('M y'),
                'income'      => round($totalIncomePkr),
                'outgoings'   => round($totalOut),
                'saving'      => round($saving),
                'partners'    => round($partnerPkr),
                'bd'          => round($bdPkr),
                'salaries'    => round($salaryPkr),
                'expenses'    => round($expensesPkr),
            ];
        });

        // Bank balances — last 6 months per account
        $bankHistory = BankBalance::whereIn('month', $months->toArray())
            ->orderBy('month')
            ->get()
            ->groupBy('account');

        // Latest bank balances
        $latestBalances = collect(BankBalance::$accounts)->mapWithKeys(function ($label, $account) {
            $latest = BankBalance::where('account', $account)->orderByDesc('month')->first();
            $prev   = BankBalance::where('account', $account)->orderByDesc('month')->skip(1)->first();
            return [$account => [
                'label'   => $label,
                'balance' => $latest?->balance_pkr ?? 0,
                'month'   => $latest?->month ?? '—',
                'change'  => $latest && $prev ? $latest->balance_pkr - $prev->balance_pkr : null,
            ]];
        });

        $totalBankBalance = $latestBalances->sum('balance');

        // BD performance last 3 months
        $bdUsers = User::whereIn('email', ['ayubkhokhar786@gmail.com', 'alihasanwebpenter@gmail.com'])->get();
        $bdMonths = $months->take(-3);
        $bdPerformance = $bdUsers->map(function ($user) use ($bdMonths) {
            return [
                'name' => $user->name,
                'data' => $bdMonths->map(function ($month) use ($user) {
                    $from = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                    $to   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                    $achieved = Income::whereNull('deleted_at')
                        ->where('user_id', $user->id)
                        ->whereBetween('transaction_date', [$from, $to])
                        ->sum('amount');
                    $target = BdMonthlyTarget::where('user_id', $user->id)->where('month', $month)->value('target_usd') ?? 0;
                    return ['month' => Carbon::createFromFormat('Y-m', $month)->format('M y'), 'achieved' => $achieved, 'target' => $target];
                }),
            ];
        });

        return view('vendor.voyager.financials.charts', compact(
            'chartData', 'bankHistory', 'latestBalances', 'totalBankBalance', 'bdPerformance', 'months'
        ));
    }

    // ── Record bank balance (daily snapshot) ─────────────────────────────────
    public function storeBankBalance(Request $request)
    {
        $request->validate([
            'recorded_on' => 'required|date',
            'accounts'    => 'required|array',
        ]);

        $date  = \Carbon\Carbon::parse($request->recorded_on);
        $month = $date->format('Y-m');
        $saved = 0;

        foreach ($request->accounts as $account => $balance) {
            if ($balance === null || $balance === '') continue;
            BankBalance::create([
                'month'       => $month,
                'recorded_on' => $date->toDateString(),
                'account'     => $account,
                'balance_pkr' => $balance,
                'note'        => $request->note ?? null,
                'recorded_by' => auth()->id(),
            ]);
            $saved++;
        }

        return back()->with('success', "{$saved} balance(s) recorded for " . $date->format('d M Y') . '.');
    }

    // ── Cash tracker ──────────────────────────────────────────────────────────
    public function cash(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $from  = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $to    = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $transactions = CashTransaction::whereBetween('date', [$from, $to])
            ->orderByDesc('date')->orderByDesc('id')
            ->get();

        $totalIn  = $transactions->where('type', 'in')->sum('amount');
        $totalOut = $transactions->where('type', 'out')->sum('amount');
        $balance  = $totalIn - $totalOut;

        $byCategory = $transactions->where('type', 'out')
            ->groupBy('category')
            ->map(fn($g) => $g->sum('amount'))
            ->sortDesc();

        // Last 3 months for context
        $recentMonths = collect();
        for ($i = 2; $i >= 0; $i--) {
            $m    = now()->subMonths($i)->format('Y-m');
            $mFrom = \Carbon\Carbon::createFromFormat('Y-m', $m)->startOfMonth();
            $mTo   = \Carbon\Carbon::createFromFormat('Y-m', $m)->endOfMonth();
            $recentMonths->put($m, [
                'label'  => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y'),
                'in'     => CashTransaction::whereBetween('date', [$mFrom, $mTo])->where('type', 'in')->sum('amount'),
                'out'    => CashTransaction::whereBetween('date', [$mFrom, $mTo])->where('type', 'out')->sum('amount'),
            ]);
        }

        return view('vendor.voyager.financials.cash', compact(
            'transactions', 'totalIn', 'totalOut', 'balance',
            'byCategory', 'month', 'recentMonths'
        ));
    }

    public function storeCash(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'type'        => 'required|in:in,out',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        CashTransaction::create([
            'date'        => $request->date,
            'type'        => $request->type,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Cash entry saved.');
    }

    public function destroyCash(int $id)
    {
        CashTransaction::findOrFail($id)->delete();
        return back()->with('success', 'Entry deleted.');
    }

    // ── Save monthly expense ───────────────────────────────────────────────────
    public function storeExpense(Request $request)
    {
        $request->validate([
            'month'      => 'required|regex:/^\d{4}-\d{2}$/',
            'category'   => 'required|string',
            'amount_pkr' => 'required|numeric|min:0',
            'paid_from'  => 'nullable|string',
            'note'       => 'nullable|string|max:255',
        ]);

        MonthlyExpense::create([
            'month'      => $request->month,
            'category'   => $request->category,
            'amount_pkr' => $request->amount_pkr,
            'is_fixed'   => (bool) $request->is_fixed,
            'paid_from'  => $request->paid_from,
            'note'       => $request->note,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Expense added.');
    }

    public function destroyExpense(int $id)
    {
        MonthlyExpense::findOrFail($id)->delete();
        return back()->with('success', 'Expense deleted.');
    }

    // ── Auto-fill fixed expenses for a month ──────────────────────────────────
    public function seedFixedExpenses(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $added = 0;
        foreach (MonthlyExpense::$fixedDefaults as $row) {
            $exists = MonthlyExpense::where('month', $month)->where('category', $row['category'])->where('is_fixed', true)->exists();
            if (!$exists) {
                MonthlyExpense::create(array_merge($row, ['month' => $month, 'is_fixed' => true, 'created_by' => auth()->id()]));
                $added++;
            }
        }

        return back()->with('success', "{$added} fixed expense(s) added for {$month}.");
    }

    // ── Save/update BD target ─────────────────────────────────────────────────
    public function storeBdTarget(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'month'      => 'required|regex:/^\d{4}-\d{2}$/',
            'target_usd' => 'required|numeric|min:0',
        ]);

        BdMonthlyTarget::updateOrCreate(
            ['user_id' => $request->user_id, 'month' => $request->month],
            ['target_usd' => $request->target_usd, 'notes' => $request->notes]
        );

        return back()->with('success', 'Target saved.');
    }

    // ── Domain store/update ────────────────────────────────────────────────────
    public function storeDomain(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'expires_on' => 'required|date',
        ]);

        if ($request->domain_id) {
            Domain::findOrFail($request->domain_id)->update($request->except('domain_id', '_token', '_method'));
            return back()->with('success', 'Domain updated.');
        }

        Domain::create($request->except('_token'));
        return back()->with('success', 'Domain added.');
    }

    public function renewDomain(Request $request, int $id)
    {
        $request->validate([
            'renewed_expires_on' => 'required|date',
            'renewal_cost_pkr'   => 'nullable|numeric',
            'month'              => 'required|regex:/^\d{4}-\d{2}$/',
        ]);

        $domain = Domain::findOrFail($id);
        $domain->update(['expires_on' => $request->renewed_expires_on]);

        if ($request->renewal_cost_pkr) {
            MonthlyExpense::create([
                'month'      => $request->month,
                'category'   => 'domain_renewal',
                'amount_pkr' => $request->renewal_cost_pkr,
                'paid_from'  => $request->paid_from,
                'note'       => 'Domain renewal: ' . $domain->name,
                'domain_id'  => $domain->id,
                'is_fixed'   => false,
                'created_by' => auth()->id(),
            ]);
        }

        return back()->with('success', $domain->name . ' renewed.');
    }

    // ── Core P&L calculation ───────────────────────────────────────────────────
    private function buildPnl(Carbon $from, Carbon $to): array
    {
        $fromStr = $from->format('Y-m');
        $toStr   = $to->format('Y-m');

        // ── Income ─────────────────────────────────────────────────────────────
        // USD income (needs conversion rate)
        $usdIncomes = Income::whereNull('deleted_at')
            ->whereBetween('transaction_date', [$from->startOfMonth()->toDateString(), $to->endOfMonth()->toDateString()])
            ->where('amount_in', 'usd')
            ->get();

        // PKR income (direct)
        $pkrIncomes = Income::whereNull('deleted_at')
            ->whereBetween('transaction_date', [$from->startOfMonth()->toDateString(), $to->endOfMonth()->toDateString()])
            ->where('amount_in', 'pkr')
            ->get();

        $totalIncomeUsd = $usdIncomes->sum('amount');

        // Use converted_pkr if recorded, otherwise fall back to average from user_payments conversion rates
        $totalIncomePkrFromUsd = $usdIncomes->sum(fn($i) => $i->converted_pkr ?? ($i->amount * $this->avgConversionRate($from, $to)));
        $totalIncomePkrDirect  = $pkrIncomes->sum('amount');
        $totalIncomePkr        = $totalIncomePkrFromUsd + $totalIncomePkrDirect;

        // Income by bank account
        $incomeByAccount = $usdIncomes->merge($pkrIncomes)
            ->groupBy('received_in')
            ->map(fn($g) => $g->sum(fn($i) => $i->amount_in === 'usd'
                ? ($i->converted_pkr ?? $i->amount * $this->avgConversionRate($from, $to))
                : $i->amount
            ));

        // Income by source
        $incomeBySource = $usdIncomes->groupBy('source')
            ->map(fn($g) => ['usd' => $g->sum('amount'), 'pkr' => $g->sum(fn($i) => $i->converted_pkr ?? $i->amount * $this->avgConversionRate($from, $to))]);

        // ── Partner payments (37.5%) ────────────────────────────────────────────
        $partnerPayments = UserPayment::whereNull('deleted_at')
            ->where('generated_by_system', false)
            ->whereBetween('created_at', [$from->copy()->startOfMonth(), $to->copy()->endOfMonth()])
            ->selectRaw('SUM(payable) as total_pkr, SUM(dev_earning) as total_usd')
            ->first();

        // ── BD commissions ─────────────────────────────────────────────────────
        $bdPayments = UserPayment::whereNull('deleted_at')
            ->where('generated_by_system', true)
            ->whereBetween('created_at', [$from->copy()->startOfMonth(), $to->copy()->endOfMonth()])
            ->with('developer:id,name,email')
            ->get();

        $bdByDev = $bdPayments->groupBy('developer_id')->map(fn($g) => [
            'name'      => $g->first()->developer?->name ?? 'Unknown',
            'email'     => $g->first()->developer?->email ?? '',
            'total_pkr' => $g->sum('payable'),
            'total_usd' => $g->sum('dev_earning'),
            'count'     => $g->count(),
        ]);

        // ── Fixed salaries (net = gross - fines - leaves - advances) ──────────────
        $salaries = SalaryInvoiceLog::with('user:id,name,email')
            ->whereBetween('month', [$fromStr, $toStr])
            ->get();

        $salaryGrossPkr      = $salaries->where('currency', 'PKR')->sum('gross_salary');
        $salaryDeductionsPkr = $salaries->where('currency', 'PKR')->sum('total_deductions');
        $salaryTotalPkr      = $salaries->where('currency', 'PKR')->sum('net_salary');
        $salaryTotalUsd      = $salaries->where('currency', 'USD')->sum('net_salary');

        // Fines deducted this period (status=deducted, from fines table directly)
        $finesDeducted = \App\Models\Fine::whereNull('deleted_at')
            ->where('status', 'deducted')
            ->whereBetween('paid_at', [$from->copy()->startOfMonth(), $to->copy()->endOfMonth()])
            ->get();
        $finesTotal = $finesDeducted->sum('amount');

        // ── Monthly expenses ───────────────────────────────────────────────────
        $expenses = MonthlyExpense::whereBetween('month', [$fromStr, $toStr])->get();
        $expensesByCategory = $expenses->groupBy('category')->map(fn($g) => $g->sum('amount_pkr'));
        $expensesTotalPkr   = $expenses->sum('amount_pkr');

        // By account
        $expensesByAccount = $expenses->groupBy('paid_from')->map(fn($g) => $g->sum('amount_pkr'));

        // ── BD targets vs achieved ─────────────────────────────────────────────
        $bdTargetData = [];
        $bdUsers = User::whereIn('email', ['ayubkhokhar786@gmail.com', 'alihasanwebpenter@gmail.com'])->get();
        foreach ($bdUsers as $bdUser) {
            $targets = BdMonthlyTarget::where('user_id', $bdUser->id)
                ->whereBetween('month', [$fromStr, $toStr])
                ->get();

            $achieved = Income::whereNull('deleted_at')
                ->where('user_id', $bdUser->id)
                ->whereBetween('transaction_date', [$from->copy()->startOfMonth(), $to->copy()->endOfMonth()])
                ->sum('amount');

            $bdTargetData[] = [
                'name'          => $bdUser->name,
                'email'         => $bdUser->email,
                'target_usd'    => $targets->sum('target_usd'),
                'achieved_usd'  => (float) $achieved,
                'pct'           => $targets->sum('target_usd') > 0
                    ? round(($achieved / $targets->sum('target_usd')) * 100, 1)
                    : null,
            ];
        }

        // ── Summary ────────────────────────────────────────────────────────────
        $totalOutgoingsPkr = ($partnerPayments->total_pkr ?? 0)
            + $bdPayments->sum('payable')
            + $salaryTotalPkr
            + ($salaryTotalUsd * $this->avgConversionRate($from, $to))
            + $expensesTotalPkr;

        $netSavingPkr = $totalIncomePkr - $totalOutgoingsPkr;

        return compact(
            'totalIncomeUsd', 'totalIncomePkr', 'incomeByAccount', 'incomeBySource',
            'partnerPayments', 'bdByDev', 'bdPayments',
            'salaries', 'salaryGrossPkr', 'salaryDeductionsPkr', 'salaryTotalPkr', 'salaryTotalUsd',
            'finesDeducted', 'finesTotal',
            'expenses', 'expensesByCategory', 'expensesTotalPkr', 'expensesByAccount',
            'bdTargetData',
            'totalOutgoingsPkr', 'netSavingPkr'
        );
    }

    private function avgConversionRate(Carbon $from, Carbon $to): float
    {
        $avg = UserPayment::whereNull('deleted_at')
            ->whereNotNull('currency_current_rate')
            ->where('currency_current_rate', '>', 0)
            ->whereBetween('created_at', [$from->copy()->startOfMonth(), $to->copy()->endOfMonth()])
            ->avg('currency_current_rate');

        return $avg ?: 279.0; // fallback rate
    }

    private function parsePeriod(Request $request): array
    {
        $preset = $request->input('period', 'this_month');

        switch ($preset) {
            case 'last_month':
                $from  = now()->subMonth()->startOfMonth();
                $to    = now()->subMonth()->endOfMonth();
                $label = 'Last Month (' . $from->format('M Y') . ')';
                break;
            case 'last_6_months':
                $from  = now()->subMonths(5)->startOfMonth();
                $to    = now()->endOfMonth();
                $label = 'Last 6 Months';
                break;
            case 'this_year':
                $from  = now()->startOfYear();
                $to    = now()->endOfMonth();
                $label = 'This Year (' . now()->format('Y') . ')';
                break;
            case 'custom':
                $from  = Carbon::parse($request->input('from', now()->startOfMonth()));
                $to    = Carbon::parse($request->input('to',   now()->endOfMonth()));
                $label = $from->format('d M Y') . ' – ' . $to->format('d M Y');
                break;
            default: // this_month
                $from  = now()->startOfMonth();
                $to    = now()->endOfMonth();
                $label = 'This Month (' . now()->format('M Y') . ')';
        }

        return [$from, $to, $label];
    }
}
