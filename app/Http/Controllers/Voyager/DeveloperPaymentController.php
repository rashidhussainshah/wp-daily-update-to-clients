<?php

namespace App\Http\Controllers\Voyager;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Project;
use App\Models\ProjectTarget;
use App\Models\User;
use App\Models\UserPayment;
use App\Services\PaymentCalculationService;
use App\utils\traits\EmailTrait;
use App\utils\traits\ResolvesDatePresets;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class DeveloperPaymentController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    use EmailTrait, ResolvesDatePresets;

    protected PaymentCalculationService $calculator;

    public function __construct(PaymentCalculationService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Browse with advanced filters and totals for the user-payments listing.
     */
    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        if ($slug !== 'user-payments') {
            return parent::index($request);
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->authorize('browse', app($dataType->model_name));

        $filters = $this->resolveDateFilters($request->only([
            'ids', 'developer_id', 'business_developer_id', 'status', 'share_type', 'earning_type',
            'client_source', 'project_id', 'income_id', 'paid_state', 'generated', 'mismatch',
            'date_from', 'date_to', 'period',
        ]));

        // Administrator-only filter - strip it out for everyone else even
        // if forced via the URL.
        if (!isAdministrator()) {
            unset($filters['mismatch']);
        }

        $query = UserPayment::with(['developer', 'project', 'projectTarget', 'businessDeveloper', 'income', 'logs.actor'])
            ->currentUserAndManagement()
            ->filter($filters);

        $totals = (clone $query)
            ->selectRaw('COUNT(*) as requests, COALESCE(SUM(total_earning),0) as total_earning, COALESCE(SUM(dev_earning),0) as dev_earning, COALESCE(SUM(payable),0) as payable, COALESCE(SUM(paid),0) as paid')
            ->reorder()
            ->first();

        // Advance (Credit To Dev) deduction context when a single developer is filtered.
        $advancePkr = null;
        $advanceUsd = null;
        if (!empty($filters['developer_id'])) {
            $advancePkr = Expense::getAdvance($filters['developer_id'], Expense::IN_PKR);
            $advanceUsd = Expense::getAdvance($filters['developer_id'], Expense::IN_USD);
        }

        $canManage = $this->userCanManagePayments();

        // Grouped mode shows each partner request with its linked BD commission
        // as a child row. Fall back to the flat list when the filters focus on
        // BD/system rows, which grouping would otherwise hide.
        $filteredDeveloperIsBusinessDeveloper = !empty($filters['developer_id'])
            && optional(User::find($filters['developer_id']))->isBusinessDeveloper();
        $grouped = empty($filters['ids'])
            && empty($filters['mismatch'])
            && ($filters['share_type'] ?? null) !== UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER
            && ($filters['generated'] ?? null) !== 'system'
            && !$filteredDeveloperIsBusinessDeveloper;

        if ($grouped) {
            $query->with(['linkedCommission.developer', 'linkedCommission.income', 'linkedCommission.logs.actor'])
                ->where(function ($q) use ($canManage) {
                    // primary rows, plus children orphaned by a soft-deleted parent
                    $q->whereNull('second_entry_id')
                        ->orWhereDoesntHave('sourceRequest');

                    // A non-admin/accountant viewer only ever sees their own
                    // developer_id rows (see currentUserAndManagement()). If a
                    // BD commission's linked development-partner request
                    // belongs to a DIFFERENT developer, that parent row will
                    // never appear in this person's own listing to nest it
                    // under - so without this, the commission silently
                    // vanishes instead of showing as its own row.
                    if (!$canManage) {
                        $q->orWhereHas('sourceRequest', function ($sub) {
                            $sub->where('developer_id', '!=', Auth::id());
                        });
                    }
                });
        }

        // Biggest discrepancy first when reviewing mismatches.
        if (($filters['mismatch'] ?? null) === '1') {
            ['sql' => $sql, 'bindings' => $bindings] = UserPayment::payableMismatchExpression();
            $query->reorder()->orderByRaw("{$sql} DESC", $bindings);
        } elseif (in_array($filters['mismatch'] ?? null, ['35', '37.5'], true)) {
            ['sql' => $sql, 'bindings' => $bindings] = UserPayment::shareMismatchExpression((float) $filters['mismatch'] / 100);
            $query->reorder()->orderByRaw("{$sql} DESC", $bindings);
        }

        $payments = $query->paginate(25)->withQueryString();

        $developers = $canManage
            ? User::whereIn('id', UserPayment::query()->select('developer_id')->distinct()->pluck('developer_id'))->orderBy('name')->get(['id', 'name'])
            : collect();
        $businessDevelopers = User::businessDeveloper()->get(['id', 'name']);
        $projects = Project::orderBy('name')->get(['id', 'name']);
        // Searchable income filter, administrators only.
        $incomes = isAdministrator() ? Income::orderByDesc('id')->get() : collect();

        return Voyager::view('voyager::user-payments.browse', [
            'dataType' => $dataType,
            'payments' => $payments,
            'totals' => $totals,
            'filters' => $filters,
            'developers' => $developers,
            'businessDevelopers' => $businessDevelopers,
            'projects' => $projects,
            'incomes' => $incomes,
            'advancePkr' => $advancePkr,
            'advanceUsd' => $advanceUsd,
            'canManage' => $canManage,
            // Rate & Approve is an accountant-only action.
            'canRateApprove' => $this->canRateApprovePayments(),
            // Mark Paid is restricted to a single person.
            'canPay' => Auth::id() === User::RASHID_USER_ID,
            'grouped' => $grouped,
        ]);
    }

    /**
     * Administrator statistics page for the payments module. Uses the same
     * filters as the listing so numbers can be sliced the same way.
     */
    public function statistics(Request $request)
    {
        if (!isAdministrator()) {
            abort(403, 'Only administrators can view payment statistics.');
        }

        $filters = $this->resolveDateFilters($request->only([
            'developer_id', 'business_developer_id', 'status', 'share_type', 'earning_type',
            'client_source', 'project_id', 'income_id', 'paid_state', 'generated',
            'date_from', 'date_to', 'period',
        ]));

        $base = UserPayment::query()->filter($filters);

        // Gross volume must not double-count the partner request + auto BD
        // commission pair carrying the same total_earning: count only primary
        // rows (not system-generated copies), same dedupe FinancialsController
        // uses. Historic income_id values are reused as buckets, so grouping
        // by income would badly understate gross. Pre-2024 BD rows predate the
        // generated_by_system flag, so a manual BD row that mirrors a partner
        // request (same project + amount) is also excluded as a duplicate.
        $primaryRows = $this->primaryEarnings(clone $base)
            ->selectRaw('client_source, COUNT(*) as cnt, COALESCE(SUM(total_earning),0) as gross')
            ->groupBy('client_source')
            ->get();

        $grossTotal = (float) $primaryRows->sum('gross');
        $primaryCount = (int) $primaryRows->sum('cnt');
        $bySource = [];
        foreach ($primaryRows as $row) {
            $gross = (float) $row->gross;
            $fee = round($gross * $this->calculator->feeRate($row->client_source), 2);
            $bySource[$row->client_source ?: 'unknown'] = [
                'incomes' => $row->cnt,
                'gross' => $gross,
                'fee' => $fee,
                'net' => round($gross - $fee, 2),
            ];
        }
        $fiverrFee = $bySource['fiverr']['fee'] ?? 0;
        $upworkFee = $bySource['upwork']['fee'] ?? 0;
        $totalFees = round(array_sum(array_column($bySource, 'fee')), 2);
        $netAfterFees = round($grossTotal - $totalFees, 2);

        $partnerShare = (float) (clone $base)->where('share_type', UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER)->sum('dev_earning');
        $bdShare = (float) (clone $base)->where('share_type', UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)->sum('dev_earning');
        $companyRemainder = round($netAfterFees - $partnerShare - $bdShare, 2);

        // Average PKR rate over the filtered rows, to express the USD figures
        // in PKR (same approach as FinancialsController::avgConversionRate).
        $avgRate = (float) ((clone $base)->whereNotNull('currency_current_rate')->where('currency_current_rate', '>', 0)->avg('currency_current_rate') ?: 279.0);

        $pkr = (clone $base)
            ->selectRaw('COUNT(*) as requests, COALESCE(SUM(payable),0) as payable, COALESCE(SUM(paid),0) as paid')
            ->first();
        $unpaidPkr = (float) (clone $base)->approved()->notPaid()->sum('payable');
        $awaitingRate = (clone $base)->requested()->count();

        // Actual PKR payable/paid split per share type (real sums, not rate estimates).
        $pkrByShareType = (clone $base)
            ->selectRaw('share_type, COALESCE(SUM(payable),0) as payable, COALESCE(SUM(paid),0) as paid')
            ->groupBy('share_type')
            ->get()
            ->keyBy('share_type');

        // Per-source share split for the breakdown table.
        $shareBySource = (clone $base)
            ->selectRaw('client_source, share_type, COALESCE(SUM(dev_earning),0) as share')
            ->groupBy('client_source', 'share_type')
            ->get()
            ->groupBy('client_source');

        // Per-user breakdowns, split by share type.
        $byUser = (clone $base)
            ->selectRaw('developer_id, share_type, COUNT(*) as requests, COALESCE(SUM(total_earning),0) as gross, COALESCE(SUM(dev_earning),0) as share, COALESCE(SUM(payable),0) as payable, COALESCE(SUM(paid),0) as paid')
            ->groupBy('developer_id', 'share_type')
            ->with('developer:id,name')
            ->get();
        $partnersBreakdown = $byUser->where('share_type', UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER)->values();
        $bdBreakdown = $byUser->where('share_type', UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)->values();
        $advances = $partnersBreakdown->mapWithKeys(fn($row) => [
            $row->developer_id => [
                'pkr' => Expense::getAdvance($row->developer_id, Expense::IN_PKR),
                'usd' => Expense::getAdvance($row->developer_id, Expense::IN_USD),
            ],
        ]);

        $byEarningType = (clone $base)
            ->selectRaw('earning_type, COUNT(*) as requests, COALESCE(SUM(dev_earning),0) as share, COALESCE(SUM(payable),0) as payable')
            ->groupBy('earning_type')
            ->get();

        // Monthly series for the charts: last 12 months unless a date filter narrows it.
        $monthlyQuery = UserPayment::query()->filter($filters);
        if (empty($filters['date_from']) && empty($filters['date_to'])) {
            $monthlyQuery->where('created_at', '>=', now()->subMonths(11)->startOfMonth());
        }
        $monthlyRows = (clone $monthlyQuery)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, share_type, COALESCE(SUM(dev_earning),0) as share, COUNT(*) as requests")
            ->groupBy('month', 'share_type')
            ->orderBy('month')
            ->get();
        $monthlyGross = $this->primaryEarnings(clone $monthlyQuery)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COALESCE(SUM(total_earning),0) as gross")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('gross', 'month');
        $months = $monthlyRows->pluck('month')->merge($monthlyGross->keys())->unique()->sort()->values();
        $monthly = [
            'labels' => $months,
            'partner' => $months->map(fn($m) => (float) $monthlyRows->where('month', $m)->where('share_type', UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER)->sum('share')),
            'bd' => $months->map(fn($m) => (float) $monthlyRows->where('month', $m)->where('share_type', UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)->sum('share')),
            // gross only from primary rows so partner + commission pairs count once
            'gross' => $months->map(fn($m) => (float) ($monthlyGross[$m] ?? 0)),
        ];

        $developers = User::whereIn('id', UserPayment::query()->select('developer_id')->distinct()->pluck('developer_id'))->orderBy('name')->get(['id', 'name']);
        $businessDevelopers = User::businessDeveloper()->get(['id', 'name']);
        $projects = Project::orderBy('name')->get(['id', 'name']);

        return Voyager::view('voyager::user-payments.statistics', compact(
            'filters', 'grossTotal', 'bySource', 'fiverrFee', 'upworkFee', 'totalFees', 'netAfterFees',
            'partnerShare', 'bdShare', 'companyRemainder', 'avgRate', 'pkr', 'unpaidPkr', 'awaitingRate', 'pkrByShareType',
            'shareBySource', 'partnersBreakdown', 'bdBreakdown', 'advances', 'byEarningType',
            'monthly', 'developers', 'businessDevelopers', 'projects'
        ) + ['primaryCount' => $primaryCount]);
    }

    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();

        // A payment request is only valid when tied to an income, project and target.
        $request->validate([
            'income_id' => 'required|exists:incomes,id',
            'project_id' => 'required|exists:projects,id',
            'project_target_id' => 'required|exists:project_targets,id',
            'client_source' => 'required|in:' . implode(',', UserPayment::CLIENT_SOURCES),
            'total_earning' => 'required|numeric|min:0.01',
        ]);

        $submitter = Auth::user();
        $income = Income::findOrFail($request->income_id);
        $totalEarning = (float) $request->total_earning;
        $clientSource = $request->client_source;
        // The rate field isn't on the add form (partners/BD never see it) - use
        // the income's own conversion rate so payable is already calculated
        // when the request lands, instead of sitting blank until Rate & Approve.
        $currencyRate = is_numeric($request->currency_current_rate)
            ? (float) $request->currency_current_rate
            : ($income->conversion_rate ? (float) $income->conversion_rate : null);
        $businessDevUser = $request->select_business_developer_id ? User::find($request->select_business_developer_id) : null;
        $submitterIsBusinessDeveloper = $submitter->isBusinessDeveloper();

        $this->calculator->assertEarningWithinIncome($income, $totalEarning);
        // Hard block: one request per user per income.
        $this->calculator->assertNoDuplicate($income->id, $submitter->id);

        if ($submitterIsBusinessDeveloper) {
            // Manual commission request (e.g. income earned by a salary employee):
            // only the business developer's 5-6% applies, no partner share.
            $shareType = UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER;
            $earningType = UserPayment::EARNING_TYPE_SALARY_EMPLOYEE;
            $devEarning = $this->calculator->businessDeveloperEarning($totalEarning, $clientSource, $submitter);
            $capRate = $this->calculator->businessDeveloperRate($submitter);
        } else {
            $shareType = UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER;
            $earningType = UserPayment::EARNING_TYPE_PROJECT;
            $devEarning = $this->calculator->developmentPartnerEarning($totalEarning, $clientSource, $submitter);
            $capRate = PaymentCalculationService::DEV_PARTNER_CAP;
        }

        // Hard block: never allocate beyond the allowed share of this income.
        $this->calculator->assertIncomeCapNotExceeded($income, $shareType, $clientSource, $devEarning, $capRate);

        // Pre-check the auto-generated commission BEFORE anything is saved, so
        // a blocked commission never leaves a half-created pair behind.
        $generateCommission = false;
        $commissionWarning = null;
        if (!$submitterIsBusinessDeveloper
            && $businessDevUser
            && $businessDevUser->isBusinessDeveloper()) {
            $existingCommission = $this->calculator->existingRequestForIncome($income->id, $businessDevUser->id);
            if ($existingCommission) {
                // Hard block on duplicates: keep the earlier manual/auto request,
                // never create a second commission for the same income.
                $commissionWarning = "No commission was generated for {$businessDevUser->name}: request #{$existingCommission->id} already exists against income #{$income->id}.";
            } else {
                $commissionShare = $this->calculator->businessDeveloperEarning($totalEarning, $clientSource, $businessDevUser);
                $this->calculator->assertIncomeCapNotExceeded(
                    $income,
                    UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER,
                    $clientSource,
                    $commissionShare,
                    $this->calculator->businessDeveloperRate($businessDevUser)
                );
                $generateCommission = true;
            }
        }

        // Server-side calculated values always win over anything typed in the form.
        $request->merge([
            'developer_id' => $submitter->id,
        ]);

        // Begin a transaction
        DB::beginTransaction();
        try {
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
            $data->share_type = $shareType;
            $data->earning_type = $earningType;
            // dev_earning/payable/fee/currency_current_rate are hidden from the
            // add form (add=0, by design - partners/BD never see rates or
            // shares), so Voyager's insertUpdateData() skips them entirely and
            // would otherwise save them as null. Assign the already-computed
            // values explicitly.
            $data->dev_earning = $devEarning;
            $data->currency_current_rate = $currencyRate;
            $data->payable = $this->calculator->payable($devEarning, $currencyRate);
            $data->fee = (int) round($this->calculator->feeRate($clientSource) * 100);
            $data->save();

            event(new BreadDataAdded($dataType, $data));
            $this->sendEmail($data);

            if ($generateCommission) {
                $this->createBusinessDeveloperEntry($request, $data, $businessDevUser, $currencyRate);
            }

            DB::commit();

            if (!$request->has('_tagging')) {
                if (auth()->user()->can('browse', $data)) {
                    $redirect = redirect()->route("voyager.{$dataType->slug}.index");
                } else {
                    $redirect = redirect()->back();
                }

                $flash = [
                    'message' => __('voyager::generic.successfully_added_new') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ];
                if ($commissionWarning) {
                    $flash['message'] .= ' | ' . $commissionWarning;
                    $flash['alert-type'] = 'warning';
                }

                return $redirect->with($flash);
            } else {
                return response()->json(['success' => true, 'data' => $data]);
            }
        } catch (\Exception $e) {
            // If an exception occurs, roll back the transaction
            DB::rollback();
            // Handle the exception as needed
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Auto-generate the business developer commission entry linked to a
     * development partner request. Replaces the old per-user copies.
     */
    private function createBusinessDeveloperEntry(Request $request, UserPayment $sourcePayment, User $businessDevUser, ?float $currencyRate): void
    {
        $totalEarning = (float) $request->input('total_earning');
        $clientSource = $request->input('client_source');

        $devEarning = $this->calculator->businessDeveloperEarning($totalEarning, $clientSource, $businessDevUser);

        $up = new UserPayment();
        $up->developer_id = $businessDevUser->id;
        $up->income_id = $request->income_id;
        $up->project_id = $request->project_id;
        $up->project_target_id = $request->project_target_id;
        $up->client_source = $clientSource;
        $up->total_earning = $totalEarning;
        $up->dev_earning = $devEarning;
        $up->payable = $this->calculator->payable($devEarning, $currencyRate);
        $up->fee = (int) round($this->calculator->feeRate($clientSource) * 100);
        $up->currency_current_rate = $currencyRate;
        $up->share_type = UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER;
        $up->earning_type = UserPayment::EARNING_TYPE_PROJECT;
        $up->notes = trim(($request->input('notes') ?? '') . "\nAutomatically generated by system");
        $up->second_entry_id = $sourcePayment->id;
        $up->generated_by_system = true;
        $up->select_business_developer_id = $businessDevUser->id;
        $up->save();

        $commission = UserPayment::with(['developer', 'project', 'projectTarget'])->find($up->id);
        $this->sendEmail($commission);
    }

    /**
     * When a development partner request is approved, approve its linked
     * commission with a payable recalculated at the same PKR rate.
     */
    private function approveLinkedCommission(?float $currencyRate, UserPayment $developerPaymentReq): void
    {
        $commission = UserPayment::with(['developer', 'project', 'projectTarget'])
            ->where('second_entry_id', $developerPaymentReq->id)
            ->where('status', UserPayment::REQUESTED_STATUS)
            ->first();

        if (!$commission || !$currencyRate) {
            return;
        }

        $commission->payable = $this->calculator->payable((float) $commission->dev_earning, $currencyRate);
        $commission->currency_current_rate = $currencyRate;
        $commission->fee = $developerPaymentReq->fee;
        $commission->status = UserPayment::APPROVED_STATUS;
        $commission->notes = trim(($commission->notes ?? '') . "\nAutomatically approved and calculate payable by system");
        $commission->save();

        $this->sendEmail($commission, true);
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        if (!isAdministrator()) {
            return redirect()->back()->with([
                'message' => 'Only administrators can edit payment requests.',
                'alert-type' => 'error',
            ]);
        }

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Recalculate payable server-side whenever a PKR rate is present,
        // instead of trusting the value typed in the form.
        $currencyRate = is_numeric($request->currency_current_rate) ? (float) $request->currency_current_rate : null;
        if ($currencyRate && $data->dev_earning) {
            $data->payable = $this->calculator->payable((float) $data->dev_earning, $currencyRate);
            $data->save();
        }

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        event(new BreadDataUpdated($dataType, $data));
        if ($data->status == UserPayment::APPROVED_STATUS) {
            $this->sendEmail($data, true);
            $this->approveLinkedCommission($currencyRate, $data);
        }
        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    /**
     * One-click accountant action from the listing: set the PKR rate,
     * calculate payable server-side and approve the request together with
     * its linked commission.
     */
    public function rateApprove(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // Administrators can still approve (e.g. covering for the accountant),
        // they just don't get the button in the listing - see canRateApprove
        // in index(), which stays accountant-only for the UI.
        if (!$this->canRateApprovePayments() && !isAdministrator()) {
            return redirect()->back()->with(['message' => 'You are not allowed to approve payment requests.', 'alert-type' => 'error']);
        }

        $request->validate([
            'currency_current_rate' => 'required|numeric|min:1',
        ]);

        $userPayment = UserPayment::with('developer')->find($id);
        if (!$userPayment) {
            return redirect()->back()->with(['message' => 'Payment not found.', 'alert-type' => 'error']);
        }
        if ($userPayment->status !== UserPayment::REQUESTED_STATUS) {
            return redirect()->back()->with(['message' => "Payment #{$id} is not in Requested status.", 'alert-type' => 'error']);
        }

        // Self-heal: older/broken rows may have no share saved yet - calculate
        // it now with the same PaymentCalculationService used by store()/edit,
        // using the request owner's rate (their percentage override, else the
        // standard development partner / business developer default).
        if (!$userPayment->dev_earning && $userPayment->total_earning && $userPayment->developer) {
            $userPayment->dev_earning = $userPayment->share_type === UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER
                ? $this->calculator->businessDeveloperEarning((float) $userPayment->total_earning, $userPayment->client_source, $userPayment->developer)
                : $this->calculator->developmentPartnerEarning((float) $userPayment->total_earning, $userPayment->client_source, $userPayment->developer);
        }

        $currencyRate = (float) $request->currency_current_rate;
        $userPayment->currency_current_rate = $currencyRate;
        $userPayment->payable = $this->calculator->payable((float) $userPayment->dev_earning, $currencyRate);
        $userPayment->status = UserPayment::APPROVED_STATUS;
        $userPayment->notes = trim(($userPayment->notes ?? '') . "\nApproved with rate {$currencyRate} through quick approve on " . now()->format('Y-m-d H:i:s'));
        $userPayment->save();

        $this->sendEmail($userPayment, true);
        $this->approveLinkedCommission($currencyRate, $userPayment);

        return redirect()->back()->with(['message' => "Payment #{$id} approved at rate {$currencyRate}.", 'alert-type' => 'success']);
    }

    public function markUserPaymentPaid($id): \Illuminate\Http\RedirectResponse
    {
        if (Auth::id() !== User::RASHID_USER_ID) {
            return redirect()->back()->with(['message' => 'You are not allowed to mark payments as paid.', 'alert-type' => 'error']);
        }

        // Find the UserPayment record by ID
        $userPayment = UserPayment::find($id);

        if (!$userPayment) {
            return redirect()->back()->with('error', 'Payment not found.');
        }

        $userPayment->paid = $userPayment->payable;
        $userPayment->status = UserPayment::APPROVED_STATUS;
        $userPayment->mark_paid_through_btn = true;
        // Retrieve the existing text from the specific field
        $oldText = $userPayment->notes;
        // New string to append
        $newString = "Approved through mark paid button";
        $currentDateTime = date('Y-m-d H:i:s'); // Getting the current date and time in the format "YYYY-MM-DD HH:MM:SS"
        $newString = $newString . ' on ' . $currentDateTime;
        // Check if the specific field already contains text
        if (!empty($oldText)) {
            // Add a line break and append the new string
            $newText = $oldText . PHP_EOL . $newString;
        } else {
            // Set the new string as the initial text
            $newText = $newString;
        }
        // Save the updated text back into the specific field
        $userPayment->notes = $newText;
        $userPayment->save();

        // Redirect the user
        return redirect()->back()->with('success', 'Payment marked as paid successfully.');
    }

    /**
     * Attach an invoice/receipt to an already-paid request - a separate,
     * administrator-only action from Mark Paid itself, since one real
     * invoice sometimes covers several payment requests at once and
     * shouldn't be forced at the moment of marking paid.
     */
    public function attachInvoice(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (!isAdministrator()) {
            return redirect()->back()->with(['message' => 'Only administrators can attach an invoice.', 'alert-type' => 'error']);
        }

        $userPayment = UserPayment::find($id);
        if (!$userPayment) {
            return redirect()->back()->with(['message' => 'Payment not found.', 'alert-type' => 'error']);
        }
        if (is_null($userPayment->paid)) {
            return redirect()->back()->with(['message' => "Payment #{$id} is not marked as paid yet.", 'alert-type' => 'error']);
        }

        $request->validate([
            'paid_attachments' => 'required|array|min:1',
            'paid_attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $existing = json_decode($userPayment->paid_attachments ?? '[]', true) ?: [];
        $disk = config('voyager.storage.disk');
        $folder = 'user-payments/paid/' . now()->format('FY');

        foreach ($request->file('paid_attachments') as $file) {
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folder, $filename, $disk);
            $existing[] = $folder . '/' . $filename;
        }

        $userPayment->paid_attachments = json_encode($existing);
        $userPayment->save();

        return redirect()->back()->with(['message' => "Invoice attached to payment #{$id}.", 'alert-type' => 'success']);
    }

    /**
     * DELETE - only the Administrator role may delete payment requests.
     * Deleting a partner request also removes its auto-generated commission
     * so no orphaned BD payment can be approved or paid later.
     */
    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        if ($slug !== 'user-payments') {
            return parent::destroy($request, $id);
        }

        if (!isAdministrator()) {
            return redirect()->back()->with([
                'message' => 'Only administrators can delete payment requests.',
                'alert-type' => 'error',
            ]);
        }

        $userPayment = UserPayment::find($id);
        if (!$userPayment) {
            return redirect()->back()->with(['message' => 'Payment not found.', 'alert-type' => 'error']);
        }

        $message = "Payment request #{$id} deleted.";

        $linked = UserPayment::where('second_entry_id', $userPayment->id)
            ->where('generated_by_system', true)
            ->first();
        if ($linked) {
            $linked->delete();
            $message .= " Linked auto-generated commission #{$linked->id} was deleted with it.";
        }

        $userPayment->delete();

        return redirect()->route('voyager.user-payments.index')->with([
            'message' => $message,
            'alert-type' => 'success',
        ]);
    }

    /**
     * Create a minimal project from inside the payment request form.
     */
    public function quickAddProject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:users,id',
        ]);

        $project = new Project();
        $project->name = $request->name;
        $project->client_id = $request->client_id ?: Auth::id();
        $project->payment_mode = 'Direct';
        $project->start_date = now();
        $project->expected_delivery_date = now();
        $project->save();

        return response()->json(['id' => $project->id, 'name' => $project->name]);
    }

    /**
     * Create a minimal project target from inside the payment request form.
     */
    public function quickAddTarget(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $target = new ProjectTarget();
        $target->project_id = $request->project_id;
        $target->developer_id = Auth::id();
        $target->title = $request->title;
        $target->save();

        return response()->json(['id' => $target->id, 'title' => $target->title]);
    }

    /**
     * Payment flow information page for partners, business developers,
     * the accountant and administrators.
     */
    public function flowGuide()
    {
        return Voyager::view('voyager::user-payments.flow-guide');
    }

    /**
     * Restrict a UserPayment query to primary earning rows for gross-volume
     * statistics: excludes auto-generated commission copies and legacy manual
     * BD rows that mirror a partner request on the same project and amount.
     */
    private function primaryEarnings($query)
    {
        return $query
            ->where('generated_by_system', false)
            ->where(function ($q) {
                $q->where('share_type', '!=', UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)
                    ->orWhereNotExists(function ($sub) {
                        $sub->selectRaw('1')
                            ->from('user_payments as partner_req')
                            ->whereColumn('partner_req.project_id', 'user_payments.project_id')
                            ->whereColumn('partner_req.total_earning', 'user_payments.total_earning')
                            ->where('partner_req.share_type', UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER)
                            ->whereNull('partner_req.deleted_at');
                    });
            });
    }

    private function userCanManagePayments(): bool
    {
        $user = Auth::user();

        return $user && $user->role && in_array($user->role->name, [
            User::ADMINISTRATOR_ROLE_NAME,
            User::ACCOUNTANT_ROLE_NAME,
        ]);
    }

    /**
     * Controls the Rate & Approve button in the listing - accountant-only,
     * even though administrators are also allowed to actually perform the
     * action server-side (see rateApprove()) if they ever need to.
     */
    private function canRateApprovePayments(): bool
    {
        $user = Auth::user();

        return $user && $user->role && $user->role->name === User::ACCOUNTANT_ROLE_NAME;
    }
}
