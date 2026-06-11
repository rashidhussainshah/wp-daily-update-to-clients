@extends('voyager::master')
@section('page_title', 'Cash Tracker')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-dollar"></i> Cash Tracker</h1>
    <a href="{{ route('financials.charts') }}" class="btn btn-default btn-sm">
        <i class="voyager-bar-chart"></i> Charts
    </a>
    <a href="{{ route('financials.index') }}" class="btn btn-default btn-sm">
        <i class="voyager-list"></i> P&amp;L
    </a>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Month picker --}}
<form method="GET" class="form-inline" style="margin-bottom:16px;">
    <label style="margin-right:8px;font-weight:600;">Month:</label>
    <input type="month" name="month" value="{{ $month }}" class="form-control input-sm" onchange="this.form.submit()">
</form>

<div class="row">

{{-- LEFT: Quick-entry form ──────────────────────────────────────────────── --}}
<div class="col-md-4">

    {{-- Cash balance card --}}
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:20px;margin-bottom:16px;">
        <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:1px;">Cash Balance — {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('M Y') }}</div>
        <div style="font-size:28px;font-weight:700;color:{{ $balance >= 0 ? '#34d399' : '#f87171' }};margin:8px 0;">
            Rs {{ number_format($balance, 0) }}
        </div>
        <div style="display:flex;gap:20px;font-size:12px;">
            <span style="color:#34d399;">▲ In: Rs {{ number_format($totalIn,0) }}</span>
            <span style="color:#f87171;">▼ Out: Rs {{ number_format($totalOut,0) }}</span>
        </div>
    </div>

    {{-- Quick entry --}}
    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">Add Entry</h3></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('financials.store-cash') }}">
                @csrf

                {{-- In / Out toggle --}}
                <div class="form-group" style="margin-bottom:10px;">
                    <div class="btn-group btn-group-justified">
                        <label class="btn btn-sm" id="btn-out" style="background:#ef4444;color:#fff;border:none;">
                            <input type="radio" name="type" value="out" checked style="display:none;"> ▼ Spent (Out)
                        </label>
                        <label class="btn btn-sm" id="btn-in" style="background:#e5e7eb;color:#374151;border:none;">
                            <input type="radio" name="type" value="in" style="display:none;"> ▲ Received (In)
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:8px;">
                    <label style="font-size:11px;">Amount (Rs)</label>
                    <input type="number" name="amount" class="form-control input-sm" placeholder="e.g. 2000" required min="1" step="1" autofocus>
                </div>

                <div class="form-group" style="margin-bottom:8px;">
                    <label style="font-size:11px;">Category</label>
                    <select name="category" class="form-control input-sm" required>
                        @foreach(\App\Models\CashTransaction::$categories as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:8px;">
                    <label style="font-size:11px;">Description (optional)</label>
                    <input type="text" name="description" class="form-control input-sm" placeholder="e.g. 6 cold drinks for team">
                </div>

                <div class="form-group" style="margin-bottom:12px;">
                    <label style="font-size:11px;">Date</label>
                    <input type="date" name="date" class="form-control input-sm" value="{{ today()->toDateString() }}" required>
                </div>

                <button type="submit" class="btn btn-primary btn-sm btn-block">Save Entry</button>
            </form>
        </div>
    </div>

    {{-- Breakdown by category --}}
    @if($byCategory->count())
    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">Spending by Category</h3></div>
        <div class="panel-body" style="padding:0;">
            <table class="table table-condensed" style="margin:0;">
                @foreach($byCategory as $cat => $amt)
                <tr>
                    <td>{{ \App\Models\CashTransaction::$categories[$cat] ?? ucfirst($cat) }}</td>
                    <td class="text-right" style="color:#ef4444;">Rs {{ number_format($amt,0) }}</td>
                    <td class="text-right text-muted" style="font-size:11px;">
                        {{ $totalOut > 0 ? number_format(($amt/$totalOut)*100,0) : 0 }}%
                    </td>
                </tr>
                @endforeach
                <tr style="font-weight:700;background:#fff5f5;">
                    <td>Total Spent</td>
                    <td class="text-right" style="color:#ef4444;">Rs {{ number_format($totalOut,0) }}</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- 3-month summary --}}
    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">Last 3 Months</h3></div>
        <div class="panel-body" style="padding:0;">
            <table class="table table-condensed" style="margin:0;">
                <thead><tr style="background:#f8fafc;"><th>Month</th><th class="text-right">In</th><th class="text-right">Out</th><th class="text-right">Net</th></tr></thead>
                <tbody>
                @foreach($recentMonths as $m => $row)
                @php $net = $row['in'] - $row['out']; @endphp
                <tr {{ $m === $month ? 'style=background:#f0f9ff;font-weight:600;' : '' }}>
                    <td><a href="?month={{ $m }}">{{ $row['label'] }}</a></td>
                    <td class="text-right" style="color:#16a34a;">Rs {{ number_format($row['in'],0) }}</td>
                    <td class="text-right" style="color:#dc2626;">Rs {{ number_format($row['out'],0) }}</td>
                    <td class="text-right" style="color:{{ $net >= 0 ? '#16a34a' : '#dc2626' }};">Rs {{ number_format($net,0) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- RIGHT: Transaction log ───────────────────────────────────────────────── --}}
<div class="col-md-8">
    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">
            Transactions — {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('F Y') }}
            <span class="badge" style="background:#6366f1;">{{ $transactions->count() }}</span>
        </h3></div>
        <div class="panel-body" style="padding:0;">
            @if($transactions->isEmpty())
                <p class="text-center text-muted" style="padding:30px;">No cash entries for this month. Add your first one →</p>
            @else
            <table class="table table-condensed" style="margin:0;font-size:13px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @php $runningBalance = $totalIn - $totalOut; @endphp
                @foreach($transactions->sortBy('date') as $tx)
                @php
                    if ($tx->type === 'in') $runningBalance -= $tx->amount;
                    else $runningBalance += $tx->amount;
                @endphp
                <tr>
                    <td style="white-space:nowrap;">{{ $tx->date->format('d M') }}</td>
                    <td>
                        @if($tx->type === 'out')
                            <span style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">▼ Out</span>
                        @else
                            <span style="background:#f0fdf4;color:#16a34a;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">▲ In</span>
                        @endif
                    </td>
                    <td>{{ \App\Models\CashTransaction::$categories[$tx->category] ?? $tx->category }}</td>
                    <td class="text-muted" style="font-size:12px;">{{ $tx->description ?? '—' }}</td>
                    <td class="text-right" style="font-weight:600;color:{{ $tx->type === 'out' ? '#dc2626' : '#16a34a' }};">
                        {{ $tx->type === 'out' ? '−' : '+' }} Rs {{ number_format($tx->amount,0) }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('financials.destroy-cash', $tx->id) }}" style="margin:0;" onsubmit="return confirm('Delete this entry?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-link text-danger" title="Delete"><i class="voyager-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8fafc;font-weight:700;">
                        <td colspan="4">Net Cash Balance</td>
                        <td class="text-right" style="color:{{ $balance >= 0 ? '#16a34a' : '#dc2626' }};">
                            Rs {{ number_format($balance,0) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            @endif
        </div>
    </div>

    {{-- Bank balance snapshot for today ─────────────────────────────────── --}}
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Record Bank Balance Snapshot</h3>
            <small class="text-muted" style="padding-left:8px;">Record exact balance in each account on any date</small>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('financials.store-bank-balance') }}">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label style="font-size:11px;">Date</label>
                            <input type="date" name="recorded_on" class="form-control input-sm" value="{{ today()->toDateString() }}" required>
                        </div>
                    </div>
                    @foreach(\App\Models\BankBalance::$accounts as $key => $lbl)
                    <div class="col-xs-6 col-sm-2">
                        <div class="form-group">
                            <label style="font-size:10px;color:#666;">{{ $lbl }}</label>
                            <input type="number" name="accounts[{{ $key }}]" class="form-control input-sm" placeholder="Rs" step="1" min="0">
                        </div>
                    </div>
                    @endforeach
                    <div class="col-xs-12 col-sm-2" style="padding-top:18px;">
                        <input type="text" name="note" class="form-control input-sm" placeholder="Note (optional)" style="margin-bottom:4px;">
                        <button type="submit" class="btn btn-success btn-sm btn-block">Save Snapshot</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Recent snapshots --}}
        @php
            $snapshots = \App\Models\BankBalance::whereNotNull('recorded_on')
                ->where('month', $month)
                ->orderByDesc('recorded_on')->orderByDesc('id')
                ->get()
                ->groupBy(fn($b) => $b->recorded_on->format('d M Y'));
        @endphp
        @if($snapshots->count())
        <div style="border-top:1px solid #eee;">
            <table class="table table-condensed" style="margin:0;font-size:12px;">
                <thead><tr style="background:#f8fafc;"><th>Date</th><th>Account</th><th class="text-right">Balance</th><th>Note</th></tr></thead>
                <tbody>
                @foreach($snapshots as $dateLabel => $entries)
                    @foreach($entries as $b)
                    <tr>
                        <td>{{ $dateLabel }}</td>
                        <td>{{ \App\Models\BankBalance::$accounts[$b->account] ?? $b->account }}</td>
                        <td class="text-right" style="font-weight:600;">Rs {{ number_format($b->balance_pkr,0) }}</td>
                        <td class="text-muted">{{ $b->note ?? '—' }}</td>
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
</div>
</div>
@stop

@section('javascript')
<script>
// Toggle In/Out button colours
document.querySelectorAll('input[name="type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (this.value === 'out') {
            document.getElementById('btn-out').style.background = '#ef4444';
            document.getElementById('btn-out').style.color = '#fff';
            document.getElementById('btn-in').style.background = '#e5e7eb';
            document.getElementById('btn-in').style.color = '#374151';
        } else {
            document.getElementById('btn-in').style.background = '#22c55e';
            document.getElementById('btn-in').style.color = '#fff';
            document.getElementById('btn-out').style.background = '#e5e7eb';
            document.getElementById('btn-out').style.color = '#374151';
        }
    });
});
</script>
@stop
