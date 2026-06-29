@extends('voyager::master')
@section('page_title', 'Financials — Charts & Bank')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-bar-chart"></i> Charts &amp; Bank Balances</h1>
    <a href="{{ route('financials.index') }}" class="btn btn-default btn-sm">
        <i class="voyager-list"></i> P&amp;L Detail
    </a>
    <a href="{{ route('financials.cash') }}" class="btn btn-warning btn-sm">
        <i class="voyager-dollar"></i> Cash Tracker
    </a>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- ── Bank balance cards ─────────────────────────────────────────────────── --}}
<div class="row" style="margin-bottom:8px;">
    @foreach($latestBalances as $account => $info)
    @php $change = $info['change']; @endphp
    <div class="col-md-3 col-sm-6">
        <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;margin-bottom:12px;">
            <div style="font-size:11px;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">
                {{ $info['label'] }}
            </div>
            <div style="font-size:24px;font-weight:700;color:#f8fafc;">
                Rs {{ number_format($info['balance'],0) }}
            </div>
            <div style="font-size:11px;margin-top:4px;">
                @if($change !== null)
                    <span style="color:{{ $change >= 0 ? '#34d399' : '#f87171' }};">
                        {{ $change >= 0 ? '▲' : '▼' }} Rs {{ number_format(abs($change),0) }} vs last month
                    </span>
                @else
                    <span style="color:rgba(255,255,255,0.3);">{{ $info['month'] }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-md-3 col-sm-6">
        <div class="panel" style="background:#064e3b;border:none;border-radius:8px;padding:18px 20px;margin-bottom:12px;">
            <div style="font-size:11px;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">
                Total Across All Accounts
            </div>
            <div style="font-size:24px;font-weight:700;color:#34d399;">
                Rs {{ number_format($totalBankBalance,0) }}
            </div>
            <div style="font-size:11px;margin-top:4px;color:rgba(255,255,255,0.3);">Combined closing balance</div>
        </div>
    </div>
</div>

{{-- ── Record bank balances form — ONE simple form ────────────────────────── --}}
<div class="panel panel-bordered" style="margin-bottom:20px;">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="voyager-dollar"></i> Record This Month's Bank Balances
            <small class="text-muted" style="font-weight:normal;"> — do this once at month-end (takes 30 seconds)</small>
        </h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="{{ route('financials.store-bank-balance') }}">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="font-size:11px;color:#666;">Month</label>
                        <input type="month" name="month" class="form-control input-sm" value="{{ now()->format('Y-m') }}" required>
                    </div>
                </div>
                @foreach(\App\Models\BankBalance::$accounts as $key => $label)
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="font-size:11px;color:#666;">{{ $label }}</label>
                        <input type="number" name="accounts[{{ $key }}]" class="form-control input-sm"
                               placeholder="Rs balance" step="1" min="0"
                               value="{{ $latestBalances[$key]['balance'] ?? '' }}">
                    </div>
                </div>
                @endforeach
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="font-size:11px;color:#666;">Note (optional)</label>
                        <input type="text" name="note" class="form-control input-sm" placeholder="e.g. after salary">
                    </div>
                </div>
                <div class="col-md-2" style="padding-top:22px;">
                    <button type="submit" class="btn btn-success btn-sm btn-block">
                        <i class="voyager-save"></i> Save Balances
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Charts row ─────────────────────────────────────────────────────────── --}}
<div class="row">

    {{-- 6-month P&L bar chart --}}
    <div class="col-md-7">
        <div class="panel panel-bordered">
            <div class="panel-heading"><h3 class="panel-title">Monthly P&amp;L — Last 6 Months</h3></div>
            <div class="panel-body">
                <canvas id="pnlChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly savings trend --}}
    <div class="col-md-5">
        <div class="panel panel-bordered">
            <div class="panel-heading"><h3 class="panel-title">Net Saving Trend</h3></div>
            <div class="panel-body">
                <canvas id="savingChart" height="120"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="row">

    {{-- Bank balance trend --}}
    <div class="col-md-7">
        <div class="panel panel-bordered">
            <div class="panel-heading"><h3 class="panel-title">Bank Balance Trend</h3></div>
            <div class="panel-body">
                <canvas id="bankChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Expense breakdown donut --}}
    <div class="col-md-5">
        <div class="panel panel-bordered">
            <div class="panel-heading"><h3 class="panel-title">This Month — Outgoing Breakdown</h3></div>
            <div class="panel-body" style="display:flex;align-items:center;justify-content:center;">
                <canvas id="breakdownChart" height="160" style="max-width:260px;"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- BD performance --}}
<div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Business Developer Performance — Last 3 Months</h3></div>
    <div class="panel-body">
        <canvas id="bdChart" height="70"></canvas>
    </div>
</div>

{{-- Balance history table --}}
<div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Bank Balance History</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed" style="margin:0;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th>Month</th>
                    @foreach(\App\Models\BankBalance::$accounts as $acc => $lbl)
                    <th class="text-right">{{ $lbl }}</th>
                    @endforeach
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months->sortDesc() as $month)
                @php
                    $rowBalances = $bankHistory->map(fn($g) => $g->firstWhere('month', $month)?->balance_pkr ?? null);
                    $rowTotal    = $rowBalances->filter()->sum();
                @endphp
                <tr>
                    <td><strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</strong></td>
                    @foreach(\App\Models\BankBalance::$accounts as $acc => $lbl)
                    @php $bal = $bankHistory->get($acc)?->firstWhere('month', $month)?->balance_pkr ?? null; @endphp
                    <td class="text-right">
                        @if($bal !== null)
                            Rs {{ number_format($bal, 0) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-right"><strong>{{ $rowTotal ? 'Rs '.number_format($rowTotal,0) : '—' }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
@stop

@section('css')
<style>
.panel { border-radius: 6px; }
</style>
@stop

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@php
    $labels     = $chartData->pluck('label')->toJson();
    $incomes    = $chartData->pluck('income')->toJson();
    $outgoings  = $chartData->pluck('outgoings')->toJson();
    $savings    = $chartData->pluck('saving')->toJson();
    $partners   = $chartData->pluck('partners')->toJson();
    $bd         = $chartData->pluck('bd')->toJson();
    $salaries   = $chartData->pluck('salaries')->toJson();
    $expenses   = $chartData->pluck('expenses')->toJson();

    // Bank balance series
    $bankLabels = $months->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M y'))->toJson();
    $bankSeries = collect(\App\Models\BankBalance::$accounts)->keys()->mapWithKeys(function($acc) use ($bankHistory, $months) {
        return [$acc => $months->map(fn($m) => $bankHistory->get($acc)?->firstWhere('month', $m)?->balance_pkr ?? null)->toJson()];
    });

    // Last month breakdown for donut
    $lastMonth  = $chartData->last();
@endphp

const chartDefaults = { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } };

// ── P&L bar chart ──────────────────────────────────────────────────────────
new Chart(document.getElementById('pnlChart'), {
    type: 'bar',
    data: {
        labels: {!! $labels !!},
        datasets: [
            { label: 'Income', data: {!! $incomes !!},   backgroundColor: 'rgba(34,197,94,0.7)',  borderRadius: 4 },
            { label: 'Outgoings', data: {!! $outgoings !!}, backgroundColor: 'rgba(239,68,68,0.7)', borderRadius: 4 },
        ]
    },
    options: { ...chartDefaults, scales: { y: { ticks: { callback: v => 'Rs ' + (v/1000).toFixed(0) + 'k' } } } }
});

// ── Saving trend line ──────────────────────────────────────────────────────
new Chart(document.getElementById('savingChart'), {
    type: 'line',
    data: {
        labels: {!! $labels !!},
        datasets: [{
            label: 'Net Saving',
            data: {!! $savings !!},
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 5,
            pointBackgroundColor: {!! $savings !!}.map(v => v >= 0 ? '#34d399' : '#f87171'),
        }]
    },
    options: { ...chartDefaults, scales: { y: { ticks: { callback: v => 'Rs '+(v/1000).toFixed(0)+'k' } } } }
});

// ── Bank balance trend ─────────────────────────────────────────────────────
new Chart(document.getElementById('bankChart'), {
    type: 'line',
    data: {
        labels: {!! $bankLabels !!},
        datasets: [
            { label: 'Rashid — Al Habib', data: {!! $bankSeries['rashid_al_habib'] !!}, borderColor: '#0ea5e9', tension: 0.3, pointRadius: 4, spanGaps: true },
            { label: 'Rashid — Meezan',   data: {!! $bankSeries['rashid_meezan'] !!},   borderColor: '#8b5cf6', tension: 0.3, pointRadius: 4, spanGaps: true },
            { label: 'Zahid — Allied',    data: {!! $bankSeries['zahid_allied'] !!},    borderColor: '#f59e0b', tension: 0.3, pointRadius: 4, spanGaps: true },
        ]
    },
    options: { ...chartDefaults, scales: { y: { ticks: { callback: v => 'Rs '+(v/100000).toFixed(1)+'L' } } } }
});

// ── Outgoing breakdown donut ───────────────────────────────────────────────
const lastMonth = {!! json_encode($lastMonth) !!};
new Chart(document.getElementById('breakdownChart'), {
    type: 'doughnut',
    data: {
        labels: ['Partners', 'BD Comm.', 'Salaries', 'Expenses'],
        datasets: [{
            data: [lastMonth.partners, lastMonth.bd, lastMonth.salaries, lastMonth.expenses],
            backgroundColor: ['#0ea5e9','#8b5cf6','#f59e0b','#ef4444'],
            borderWidth: 2,
        }]
    },
    options: { ...chartDefaults, cutout: '65%' }
});

// ── BD performance grouped bar ─────────────────────────────────────────────
@php
    $bdMonthLabels = $bdPerformance->first()['data']->pluck('month')->toJson();
@endphp
const bdColors = ['#0ea5e9','#f59e0b'];
new Chart(document.getElementById('bdChart'), {
    type: 'bar',
    data: {
        labels: {!! $bdMonthLabels !!},
        datasets: [
            @foreach($bdPerformance as $i => $bd)
            {
                label: '{{ $bd['name'] }} — Achieved',
                data: {!! $bd['data']->pluck('achieved')->toJson() !!},
                backgroundColor: '{{ ["rgba(14,165,233,0.7)","rgba(245,158,11,0.7)"][$loop->index] }}',
                borderRadius: 4,
            },
            {
                label: '{{ $bd['name'] }} — Target',
                data: {!! $bd['data']->pluck('target')->toJson() !!},
                backgroundColor: 'transparent',
                borderColor: '{{ ["#0ea5e9","#f59e0b"][$loop->index] }}',
                borderWidth: 2,
                type: 'line',
                pointRadius: 4,
            },
            @endforeach
        ]
    },
    options: { ...chartDefaults, scales: { y: { ticks: { callback: v => '$'+v } } } }
});
</script>
@stop
