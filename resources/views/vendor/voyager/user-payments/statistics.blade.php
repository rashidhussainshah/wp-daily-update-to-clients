@extends('voyager::master')

@section('page_title', 'Payment Statistics')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-bar-chart"></i> Payment Statistics
        </h1>
        <a href="{{ route('voyager.user-payments.index', request()->query()) }}" class="btn btn-default">
            <i class="voyager-list"></i> <span>Back to Listing</span>
        </a>
        <a href="{{ route('team-statistics.index') }}" class="btn btn-default">
            <i class="voyager-people"></i> <span>Team Statistics</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')

        {{-- Filters (same set as the listing) --}}
        <div class="panel panel-bordered">
            <div class="panel-body">
                @include('voyager::user-payments._date_presets')
                <form method="GET" action="{{ route('user-payments.statistics') }}" class="form-inline">
                    <div class="form-group" style="margin:4px;">
                        <select name="developer_id" class="form-control input-sm">
                            <option value="">All Users</option>
                            @foreach($developers as $dev)
                                <option value="{{ $dev->id }}" @selected(($filters['developer_id'] ?? '') == $dev->id)>{{ $dev->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="business_developer_id" class="form-control input-sm">
                            <option value="">All Business Developers</option>
                            @foreach($businessDevelopers as $bd)
                                <option value="{{ $bd->id }}" @selected(($filters['business_developer_id'] ?? '') == $bd->id)>{{ $bd->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="status" class="form-control input-sm">
                            <option value="">All Statuses</option>
                            <option value="Requested" @selected(($filters['status'] ?? '') == 'Requested')>Requested</option>
                            <option value="Approved" @selected(($filters['status'] ?? '') == 'Approved')>Approved</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="paid_state" class="form-control input-sm">
                            <option value="">Paid + Unpaid</option>
                            <option value="paid" @selected(($filters['paid_state'] ?? '') == 'paid')>Paid</option>
                            <option value="unpaid" @selected(($filters['paid_state'] ?? '') == 'unpaid')>Unpaid</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="share_type" class="form-control input-sm">
                            <option value="">All Share Types</option>
                            <option value="development_partner" @selected(($filters['share_type'] ?? '') == 'development_partner')>Development Partner (37.5%)</option>
                            <option value="business_developer" @selected(($filters['share_type'] ?? '') == 'business_developer')>Business Developer (5-6%)</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="earning_type" class="form-control input-sm">
                            <option value="">All Earning Types</option>
                            <option value="project" @selected(($filters['earning_type'] ?? '') == 'project')>Project</option>
                            <option value="salary_employee" @selected(($filters['earning_type'] ?? '') == 'salary_employee')>Salary Employee</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="client_source" class="form-control input-sm">
                            <option value="">All Sources</option>
                            @foreach(\App\Models\UserPayment::CLIENT_SOURCES as $source)
                                <option value="{{ $source }}" @selected(($filters['client_source'] ?? '') == $source)>{{ ucfirst($source) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="project_id" class="form-control input-sm" style="max-width:180px;">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(($filters['project_id'] ?? '') == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <select name="generated" class="form-control input-sm">
                            <option value="">Manual + System</option>
                            <option value="system" @selected(($filters['generated'] ?? '') == 'system')>System generated</option>
                            <option value="manual" @selected(($filters['generated'] ?? '') == 'manual')>Manual</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <input type="number" name="income_id" class="form-control input-sm" style="width:110px;" placeholder="Income #" value="{{ $filters['income_id'] ?? '' }}">
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <input type="date" name="date_from" class="form-control input-sm" value="{{ $filters['date_from'] ?? '' }}" title="From date">
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <input type="date" name="date_to" class="form-control input-sm" value="{{ $filters['date_to'] ?? '' }}" title="To date">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin:4px;"><i class="voyager-search"></i> Apply</button>
                    <a href="{{ route('user-payments.statistics') }}" class="btn btn-default btn-sm" style="margin:4px;">Reset</a>
                </form>
            </div>
        </div>

        {{-- Row 1: money flow in USD --}}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Total Earning (Gross USD)</small>
                    <h3 style="margin:6px 0;color:#fff;">${{ number_format($grossTotal, 2) }}</h3>
                    <small style="opacity:.7;">{{ $primaryCount }} earnings &middot; {{ $pkr->requests }} payment rows</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Platform Fees</small>
                    <h3 style="margin:6px 0;color:#f87171;">-${{ number_format($totalFees, 2) }}</h3>
                    <small style="opacity:.7;">Fiverr: ${{ number_format($fiverrFee, 2) }} &middot; Upwork: ${{ number_format($upworkFee, 2) }}</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Net After Fees</small>
                    <h3 style="margin:6px 0;color:#fff;">${{ number_format($netAfterFees, 2) }}</h3>
                    <small style="opacity:.7;">Gross minus platform fees</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:{{ $companyRemainder >= 0 ? '#14432a' : '#4c1d1d' }};color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Company Remainder</small>
                    <h3 style="margin:6px 0;color:{{ $companyRemainder >= 0 ? '#34d399' : '#f87171' }};">${{ number_format($companyRemainder, 2) }}</h3>
                    <small style="opacity:.7;">Net minus partner &amp; BD shares</small>
                </div>
            </div>
        </div>

        {{-- Row 1b: the same money flow expressed in PKR at the average rate --}}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Total Earning (Gross PKR)</small>
                    <h3 style="margin:6px 0;color:#fff;">{{ number_format($grossTotal * $avgRate, 0) }}</h3>
                    <small style="opacity:.7;">at avg rate {{ number_format($avgRate, 2) }}</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Platform Fees (PKR)</small>
                    <h3 style="margin:6px 0;color:#f87171;">-{{ number_format($totalFees * $avgRate, 0) }}</h3>
                    <small style="opacity:.7;">Fiverr: {{ number_format($fiverrFee * $avgRate, 0) }} &middot; Upwork: {{ number_format($upworkFee * $avgRate, 0) }}</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:#1a1a2e;color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Net After Fees (PKR)</small>
                    <h3 style="margin:6px 0;color:#fff;">{{ number_format($netAfterFees * $avgRate, 0) }}</h3>
                    <small style="opacity:.7;">at avg rate {{ number_format($avgRate, 2) }}</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="background:{{ $companyRemainder >= 0 ? '#14432a' : '#4c1d1d' }};color:#fff;padding:16px;border-radius:6px;">
                    <small style="opacity:.7;">Company Remainder (PKR)</small>
                    <h3 style="margin:6px 0;color:{{ $companyRemainder >= 0 ? '#34d399' : '#f87171' }};">{{ number_format($companyRemainder * $avgRate, 0) }}</h3>
                    <small style="opacity:.7;">Net minus partner &amp; BD shares</small>
                </div>
            </div>
        </div>

        {{-- Row 2: shares and PKR state --}}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #1baf7a;">
                    <small class="text-muted">Development Partners Share (37.5%)</small>
                    <h4 style="margin:4px 0;">${{ number_format($partnerShare, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #eda100;">
                    <small class="text-muted">Business Developers Share (5-6%)</small>
                    <h4 style="margin:4px 0;">${{ number_format($bdShare, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #2a78d6;">
                    <small class="text-muted">Payable / Paid (PKR)</small>
                    <h4 style="margin:4px 0;">{{ number_format($pkr->payable, 2) }} <small>/ {{ number_format($pkr->paid, 2) }}</small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #d03b3b;">
                    <small class="text-muted">Unpaid Approved (PKR)</small>
                    <h4 style="margin:4px 0;">{{ number_format($unpaidPkr, 2) }}</h4>
                    <small class="text-muted">{{ $awaitingRate }} requests awaiting rate</small>
                </div>
            </div>
        </div>

        {{-- Row 2b: shares expressed in PKR --}}
        @php
            $partnerPkr = $pkrByShareType['development_partner'] ?? null;
            $bdPkr = $pkrByShareType['business_developer'] ?? null;
        @endphp
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #1baf7a;">
                    <small class="text-muted">Partners Share (PKR at avg rate {{ number_format($avgRate, 2) }})</small>
                    <h4 style="margin:4px 0;">{{ number_format($partnerShare * $avgRate, 0) }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #eda100;">
                    <small class="text-muted">BD Share (PKR at avg rate {{ number_format($avgRate, 2) }})</small>
                    <h4 style="margin:4px 0;">{{ number_format($bdShare * $avgRate, 0) }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #1baf7a;">
                    <small class="text-muted">Partners Payable / Paid (actual PKR)</small>
                    <h4 style="margin:4px 0;">{{ number_format($partnerPkr->payable ?? 0, 0) }} <small>/ {{ number_format($partnerPkr->paid ?? 0, 0) }}</small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #eda100;">
                    <small class="text-muted">BD Payable / Paid (actual PKR)</small>
                    <h4 style="margin:4px 0;">{{ number_format($bdPkr->payable ?? 0, 0) }} <small>/ {{ number_format($bdPkr->paid ?? 0, 0) }}</small></h4>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Monthly Earning vs Shares (USD)</h3></div>
                    <div class="panel-body"><canvas id="monthlyChart" height="230"></canvas></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Distribution of Gross</h3></div>
                    <div class="panel-body"><canvas id="distributionChart" height="230"></canvas></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Share by Partner (USD)</h3></div>
                    <div class="panel-body"><canvas id="partnerChart" height="230"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Breakdown tables --}}
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">By Client Source</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>Source</th><th class="text-right">Earnings</th><th class="text-right">Gross $</th><th class="text-right">Fee $</th><th class="text-right">Net $</th><th class="text-right">Partner $</th><th class="text-right">BD $</th></tr></thead>
                            <tbody>
                            @foreach($bySource as $source => $row)
                                @php
                                    $shareRows = $shareBySource[$source] ?? collect();
                                    $srcPartner = (float) $shareRows->where('share_type', 'development_partner')->sum('share');
                                    $srcBd = (float) $shareRows->where('share_type', 'business_developer')->sum('share');
                                @endphp
                                <tr>
                                    <td>{{ ucfirst($source) }} <small class="text-muted">(fee {{ (int) (app(\App\Services\PaymentCalculationService::class)->feeRate($source) * 100) }}%)</small></td>
                                    <td class="text-right">{{ $row['incomes'] }}</td>
                                    <td class="text-right">{{ number_format($row['gross'], 2) }}</td>
                                    <td class="text-right text-danger">{{ number_format($row['fee'], 2) }}</td>
                                    <td class="text-right">{{ number_format($row['net'], 2) }}</td>
                                    <td class="text-right">{{ number_format($srcPartner, 2) }}</td>
                                    <td class="text-right">{{ number_format($srcBd, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr style="font-weight:bold;">
                                <td>Total</td>
                                <td class="text-right">{{ $primaryCount }}</td>
                                <td class="text-right">{{ number_format($grossTotal, 2) }}</td>
                                <td class="text-right text-danger">{{ number_format($totalFees, 2) }}</td>
                                <td class="text-right">{{ number_format($netAfterFees, 2) }}</td>
                                <td class="text-right">{{ number_format($partnerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($bdShare, 2) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">By Earning Type</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>Type</th><th class="text-right">Requests</th><th class="text-right">Share $</th><th class="text-right">Payable PKR</th></tr></thead>
                            <tbody>
                            @foreach($byEarningType as $row)
                                <tr>
                                    <td>{{ $row->earning_type === 'salary_employee' ? 'Salary Employee (BD commission only)' : 'Project (partner + BD)' }}</td>
                                    <td class="text-right">{{ $row->requests }}</td>
                                    <td class="text-right">{{ number_format($row->share, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->payable, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Development Partners</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>Partner</th><th class="text-right">Req</th><th class="text-right">Gross $</th><th class="text-right">Share $</th><th class="text-right">Payable PKR</th><th class="text-right">Paid PKR</th><th class="text-right">Advance PKR</th><th class="text-right">Remaining PKR</th></tr></thead>
                            <tbody>
                            @foreach($partnersBreakdown as $row)
                                @php
                                    $advance = $advances[$row->developer_id]['pkr'] ?? 0;
                                    $remaining = ($row->payable - $advance) - $row->paid;
                                @endphp
                                <tr>
                                    <td>{{ $row->developer->name ?? ('#' . $row->developer_id) }}</td>
                                    <td class="text-right">{{ $row->requests }}</td>
                                    <td class="text-right">{{ number_format($row->gross, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->share, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->payable, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->paid, 2) }}</td>
                                    <td class="text-right">{{ number_format($advance, 2) }}
                                        @if(($advances[$row->developer_id]['usd'] ?? 0) > 0)
                                            <br><small class="text-muted">+ ${{ number_format($advances[$row->developer_id]['usd'], 2) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $remaining < 0 ? 'text-danger' : '' }}">{{ number_format($remaining, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Business Developers</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>Business Developer</th><th class="text-right">Commissions</th><th class="text-right">Share $</th><th class="text-right">Payable PKR</th><th class="text-right">Paid PKR</th></tr></thead>
                            <tbody>
                            @foreach($bdBreakdown as $row)
                                <tr>
                                    <td>{{ $row->developer->name ?? ('#' . $row->developer_id) }}</td>
                                    <td class="text-right">{{ $row->requests }}</td>
                                    <td class="text-right">{{ number_format($row->share, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->payable, 2) }}</td>
                                    <td class="text-right">{{ number_format($row->paid, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Fixed series colors (color follows the entity on every chart):
        // gross = blue, partner share = aqua, BD share = yellow,
        // company remainder = violet, fees = neutral gray.
        var C = { gross: '#2a78d6', partner: '#1baf7a', bd: '#eda100', company: '#4a3aa7', fees: '#898781' };
        var ink = '#52514e', grid = '#e1e0d9';

        var monthly = {!! json_encode($monthly) !!};
        var partners = {!! json_encode($partnersBreakdown->map(fn($r) => ['name' => $r->developer->name ?? ('#' . $r->developer_id), 'share' => (float) $r->share])->values()) !!};
        var distribution = {!! json_encode([
            'labels' => ['Company Remainder', 'Partners (37.5%)', 'Business Developers', 'Platform Fees'],
            'values' => [max($companyRemainder, 0), $partnerShare, $bdShare, $totalFees],
        ]) !!};

        var axisOpts = {
            grid: { color: grid },
            ticks: { color: ink, callback: function (v) { return '$' + v.toLocaleString(); } }
        };

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthly.labels,
                datasets: [
                    { label: 'Gross Earning', data: monthly.gross, backgroundColor: C.gross, borderRadius: 4, categoryPercentage: 0.7, barPercentage: 0.85 },
                    { label: 'Partner Share', data: monthly.partner, backgroundColor: C.partner, borderRadius: 4, categoryPercentage: 0.7, barPercentage: 0.85 },
                    { label: 'BD Share', data: monthly.bd, backgroundColor: C.bd, borderRadius: 4, categoryPercentage: 0.7, barPercentage: 0.85 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { color: ink, boxWidth: 12 } } },
                scales: { y: { beginAtZero: true, ...axisOpts }, x: { grid: { display: false }, ticks: { color: ink } } }
            }
        });

        new Chart(document.getElementById('distributionChart'), {
            type: 'doughnut',
            data: {
                labels: distribution.labels,
                datasets: [{
                    data: distribution.values,
                    backgroundColor: [C.company, C.partner, C.bd, C.fees],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { color: ink, boxWidth: 12 } } }
            }
        });

        new Chart(document.getElementById('partnerChart'), {
            type: 'bar',
            data: {
                labels: partners.map(function (p) { return p.name; }),
                datasets: [{ label: 'Share $', data: partners.map(function (p) { return p.share; }), backgroundColor: C.partner, borderRadius: 4, categoryPercentage: 0.6, barPercentage: 0.9 }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ...axisOpts }, y: { grid: { display: false }, ticks: { color: ink } } }
            }
        });
    </script>
@stop
