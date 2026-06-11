@extends('voyager::master')

@section('page_title', 'Financials — P&L')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-dollar"></i> Financials &amp; P&amp;L</h1>
    <a href="{{ route('financials.charts') }}" class="btn btn-info btn-sm">
        <i class="voyager-bar-chart"></i> Charts &amp; Bank Balances
    </a>
</div>
@stop

@section('content')
<div class="page-content container-fluid" style="font-size:13px;">

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ── Period filter ──────────────────────────────────────────────────────── --}}
<div class="panel panel-bordered" style="margin-bottom:16px;">
  <div class="panel-body" style="padding:12px 16px;">
    <form method="GET" class="form-inline" style="gap:8px;flex-wrap:wrap;display:flex;align-items:center;">
      <strong style="margin-right:8px;">Period:</strong>
      @foreach(['this_month'=>'This Month','last_month'=>'Last Month','last_6_months'=>'Last 6 Months','this_year'=>'This Year','custom'=>'Custom'] as $key => $lbl)
        <a href="?period={{ $key }}"
           class="btn btn-sm {{ request('period','this_month') === $key ? 'btn-primary' : 'btn-default' }}">
          {{ $lbl }}
        </a>
      @endforeach
      @if(request('period') === 'custom')
        <input type="date" name="from" value="{{ request('from') }}" class="form-control input-sm">
        <span>to</span>
        <input type="date" name="to" value="{{ request('to') }}" class="form-control input-sm">
        <input type="hidden" name="period" value="custom">
        <button type="submit" class="btn btn-sm btn-info">Apply</button>
      @endif
      <span class="text-muted" style="margin-left:8px;">Showing: <strong>{{ $label }}</strong></span>
    </form>
  </div>
</div>

{{-- ── Top summary cards ──────────────────────────────────────────────────── --}}
<div class="row">
  <div class="col-md-3 col-sm-6">
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Total Income</div>
      <div style="font-size:22px;font-weight:700;color:#22c55e;margin-top:4px;">${{ number_format($data['totalIncomeUsd'],0) }}</div>
      <div style="font-size:13px;color:rgba(255,255,255,0.6);">Rs {{ number_format($data['totalIncomePkr'],0) }}</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Total Outgoings</div>
      <div style="font-size:22px;font-weight:700;color:#ef4444;margin-top:4px;">Rs {{ number_format($data['totalOutgoingsPkr'],0) }}</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    @php $saving = $data['netSavingPkr']; @endphp
    <div class="panel" style="background:{{ $saving >= 0 ? '#064e3b' : '#7f1d1d' }};border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Net Saving</div>
      <div style="font-size:22px;font-weight:700;color:{{ $saving >= 0 ? '#34d399' : '#f87171' }};margin-top:4px;">
        Rs {{ number_format(abs($saving),0) }}
      </div>
      <div style="font-size:12px;color:rgba(255,255,255,0.5);">{{ $saving >= 0 ? 'Profit' : 'LOSS' }}</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Domains Expiring Soon</div>
      <div style="font-size:22px;font-weight:700;color:#f59e0b;margin-top:4px;">
        {{ $domains->filter(fn($d) => $d->days_until_expiry <= 30 && $d->days_until_expiry >= 0)->count() }}
      </div>
      <div style="font-size:12px;color:rgba(255,255,255,0.5);">within 30 days</div>
    </div>
  </div>
</div>

{{-- ── Main 2-col layout ──────────────────────────────────────────────────── --}}
<div class="row">

{{-- LEFT: Income + Outgoings breakdown ──────────────────────────────────── --}}
<div class="col-md-7">

  {{-- Income breakdown --}}
  <div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Income Breakdown <small class="text-muted">(auto from Income records)</small></h3></div>
    <div class="panel-body" style="padding:0;">
      <table class="table table-condensed" style="margin:0;">
        <thead><tr style="background:#f8fafc;">
          <th>Source</th><th class="text-right">USD</th><th class="text-right">PKR (est.)</th>
        </tr></thead>
        <tbody>
          @forelse($data['incomeBySource'] as $source => $row)
          <tr>
            <td><span class="label label-default">{{ ucfirst($source ?: 'Unknown') }}</span></td>
            <td class="text-right">${{ number_format($row['usd'],2) }}</td>
            <td class="text-right">Rs {{ number_format($row['pkr'],0) }}</td>
          </tr>
          @empty
          <tr><td colspan="3" class="text-center text-muted">No income records for this period.</td></tr>
          @endforelse
        </tbody>
        <tfoot><tr style="background:#f0fdf4;font-weight:700;">
          <td>TOTAL</td>
          <td class="text-right">${{ number_format($data['totalIncomeUsd'],2) }}</td>
          <td class="text-right">Rs {{ number_format($data['totalIncomePkr'],0) }}</td>
        </tr></tfoot>
      </table>
    </div>
  </div>

  {{-- Income by bank account --}}
  @if($data['incomeByAccount']->count())
  <div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Income by Bank Account</h3></div>
    <div class="panel-body" style="padding:0;">
      <table class="table table-condensed" style="margin:0;">
        <tbody>
          @foreach(\App\Models\MonthlyExpense::$bankAccounts as $key => $label)
            @if(isset($data['incomeByAccount'][$key]))
            <tr>
              <td>{{ $label }}</td>
              <td class="text-right">Rs {{ number_format($data['incomeByAccount'][$key],0) }}</td>
            </tr>
            @endif
          @endforeach
          @if(isset($data['incomeByAccount']['']) || isset($data['incomeByAccount'][null]))
          <tr><td class="text-muted">Not tagged</td><td class="text-right text-muted">Rs {{ number_format($data['incomeByAccount'][''] ?? $data['incomeByAccount'][null] ?? 0,0) }}</td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
  @endif

  {{-- Outgoings breakdown --}}
  <div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Outgoings <small class="text-muted">(auto-calculated)</small></h3></div>
    <div class="panel-body" style="padding:0;">
      <table class="table table-condensed" style="margin:0;">
        <thead><tr style="background:#f8fafc;"><th>Item</th><th class="text-right">PKR</th><th class="text-right">%</th></tr></thead>
        <tbody>
          @php
            $total = $data['totalOutgoingsPkr'] ?: 1;
            $partnerPkr = $data['partnerPayments']->total_pkr ?? 0;
            $bdPkr = $data['bdPayments']->sum('payable');
            $salaryPkr = $data['salaryTotalPkr'];
          @endphp
          <tr>
            <td><i class="voyager-people"></i> Partner Share (37.5%) <small class="text-muted">— auto</small></td>
            <td class="text-right">Rs {{ number_format($partnerPkr,0) }}</td>
            <td class="text-right text-muted">{{ $data['totalIncomePkr'] > 0 ? number_format(($partnerPkr/$data['totalIncomePkr'])*100,1) : 0 }}%</td>
          </tr>
          @foreach($data['bdByDev'] as $dev)
          <tr>
            <td style="padding-left:24px;"><small>↳ {{ $dev['name'] }} commission</small> <small class="text-muted">— auto</small></td>
            <td class="text-right">Rs {{ number_format($dev['total_pkr'],0) }}</td>
            <td class="text-right text-muted">{{ number_format(($dev['total_pkr']/$total)*100,1) }}%</td>
          </tr>
          @endforeach
          {{-- Salaries with fines/deductions breakdown --}}
          <tr>
            <td>
              <i class="voyager-person"></i> Fixed Salaries (net paid) <small class="text-muted">— auto</small>
              @if($data['salaryDeductionsPkr'] > 0)
                <br><small class="text-muted" style="padding-left:16px;">
                  Gross: Rs {{ number_format($data['salaryGrossPkr'],0) }}
                  &nbsp;−&nbsp; Deductions: Rs {{ number_format($data['salaryDeductionsPkr'],0) }}
                </small>
              @endif
            </td>
            <td class="text-right">Rs {{ number_format($salaryPkr,0) }}</td>
            <td class="text-right text-muted">{{ number_format(($salaryPkr/$total)*100,1) }}%</td>
          </tr>
          @if($data['finesTotal'] > 0)
          <tr style="background:#f0fdf4;">
            <td style="padding-left:24px;">
              <small><span style="color:#16a34a;">&#10003;</span> Fines deducted from salaries
              <span class="text-muted">({{ $data['finesDeducted']->count() }} fine(s) — reduces salary cost)</span></small>
            </td>
            <td class="text-right" style="color:#16a34a;font-weight:600;">
              − Rs {{ number_format($data['finesTotal'],0) }}
            </td>
            <td></td>
          </tr>
          @endif
          @foreach($data['salaries'] as $sal)
          <tr style="background:#fafafa;">
            <td style="padding-left:24px;font-size:11px;color:#666;">
              ↳ {{ $sal->user?->name ?? 'User #'.$sal->user_id }} — {{ $sal->month }}
              @if($sal->total_deductions > 0)
                <span class="text-muted">(gross {{ $sal->currency }} {{ number_format($sal->gross_salary,0) }} − {{ number_format($sal->total_deductions,0) }} deductions)</span>
              @endif
            </td>
            <td class="text-right" style="font-size:11px;">{{ $sal->currency }} {{ number_format($sal->net_salary,0) }}</td>
            <td></td>
          </tr>
          @endforeach
          @foreach(\App\Models\MonthlyExpense::$categories as $catKey => $catLabel)
            @if(isset($data['expensesByCategory'][$catKey]))
            <tr>
              <td>{{ $catLabel }}</td>
              <td class="text-right">Rs {{ number_format($data['expensesByCategory'][$catKey],0) }}</td>
              <td class="text-right text-muted">{{ number_format(($data['expensesByCategory'][$catKey]/$total)*100,1) }}%</td>
            </tr>
            @endif
          @endforeach
        </tbody>
        <tfoot><tr style="background:#fff5f5;font-weight:700;">
          <td>TOTAL OUTGOINGS</td>
          <td class="text-right">Rs {{ number_format($data['totalOutgoingsPkr'],0) }}</td>
          <td class="text-right"></td>
        </tr></tfoot>
      </table>
    </div>
  </div>

</div>

{{-- RIGHT: Expenses entry + BD targets + Domains ──────────────────────── --}}
<div class="col-md-5">

  {{-- ── Add variable expense — MINIMAL FORM ────────────────────────────── --}}
  <div class="panel panel-bordered">
    <div class="panel-heading" style="display:flex;align-items:center;justify-content:space-between;">
      <h3 class="panel-title">Add Expense</h3>
      {{-- Auto-fill fixed expenses --}}
      <form method="POST" action="{{ route('financials.seed-fixed') }}" style="margin:0;">
        @csrf
        <input type="hidden" name="month" value="{{ now()->format('Y-m') }}">
        <button class="btn btn-xs btn-default" title="Auto-fill fixed expenses (rent, Claude, internet) for this month">
          <i class="voyager-refresh"></i> Auto-fill Fixed
        </button>
      </form>
    </div>
    <div class="panel-body">
      <form method="POST" action="{{ route('financials.store-expense') }}">
        @csrf
        <div class="row">
          <div class="col-xs-6">
            <div class="form-group" style="margin-bottom:8px;">
              <label style="font-size:11px;">Month</label>
              <input type="month" name="month" class="form-control input-sm" value="{{ now()->format('Y-m') }}" required>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group" style="margin-bottom:8px;">
              <label style="font-size:11px;">Amount (PKR)</label>
              <input type="number" name="amount_pkr" class="form-control input-sm" placeholder="e.g. 8500" required min="0" step="1">
            </div>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:8px;">
          <label style="font-size:11px;">Category</label>
          <select name="category" class="form-control input-sm" required>
            @foreach(\App\Models\MonthlyExpense::$categories as $key => $lbl)
              @if(!in_array($key, ['rent','claude_accounts','internet_moon','internet_prime']))
                <option value="{{ $key }}">{{ $lbl }}</option>
              @endif
            @endforeach
            <optgroup label="─ Fixed (use Auto-fill button instead) ─">
              <option value="rent">Office Rent</option>
              <option value="claude_accounts">Claude AI Accounts</option>
              <option value="internet_moon">Internet — Moon</option>
              <option value="internet_prime">Internet — Prime</option>
            </optgroup>
          </select>
        </div>
        <div class="row">
          <div class="col-xs-6">
            <div class="form-group" style="margin-bottom:8px;">
              <label style="font-size:11px;">Paid From</label>
              <select name="paid_from" class="form-control input-sm">
                <option value="">— not specified —</option>
                @foreach(\App\Models\MonthlyExpense::$bankAccounts as $k => $v)
                  <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group" style="margin-bottom:8px;">
              <label style="font-size:11px;">Note (optional)</label>
              <input type="text" name="note" class="form-control input-sm" placeholder="e.g. Jun bill">
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm btn-block">Add Expense</button>
      </form>
    </div>

    {{-- Expense list for current period --}}
    @if($data['expenses']->count())
    <div style="border-top:1px solid #eee;">
      <table class="table table-condensed" style="margin:0;font-size:12px;">
        <tbody>
          @foreach($data['expenses'] as $exp)
          <tr>
            <td>{{ $exp->month }}</td>
            <td>{{ $exp->category_label }}</td>
            <td>{{ \App\Models\MonthlyExpense::$bankAccounts[$exp->paid_from] ?? '—' }}</td>
            <td class="text-right">Rs {{ number_format($exp->amount_pkr,0) }}</td>
            <td>
              <form method="POST" action="{{ route('financials.destroy-expense', $exp->id) }}" style="margin:0;" onsubmit="return confirm('Delete this expense?')">
                @csrf @method('DELETE')
                <button class="btn btn-xs btn-danger"><i class="voyager-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- ── BD Targets ──────────────────────────────────────────────────────── --}}
  <div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">BD Monthly Targets</h3></div>
    <div class="panel-body">
      @foreach($data['bdTargetData'] as $bd)
      @php $pct = $bd['pct']; @endphp
      <div style="margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
          <strong>{{ $bd['name'] }}</strong>
          <span>
            @if($bd['target_usd'])
              <span class="{{ $pct >= 100 ? 'text-success' : ($pct >= 60 ? 'text-warning' : 'text-danger') }}">
                ${{ number_format($bd['achieved_usd'],0) }} / ${{ number_format($bd['target_usd'],0) }}
                ({{ $pct }}%)
              </span>
            @else
              <span class="text-muted">${{ number_format($bd['achieved_usd'],0) }} achieved — no target set</span>
            @endif
          </span>
        </div>
        @if($bd['target_usd'])
        <div class="progress" style="margin:0;height:8px;">
          <div class="progress-bar {{ $pct >= 100 ? 'progress-bar-success' : ($pct >= 60 ? 'progress-bar-warning' : 'progress-bar-danger') }}"
               style="width:{{ min($pct,100) }}%"></div>
        </div>
        @endif
      </div>
      @endforeach

      <hr style="margin:10px 0;">
      <form method="POST" action="{{ route('financials.store-bd-target') }}">
        @csrf
        <div class="row">
          <div class="col-xs-5">
            <select name="user_id" class="form-control input-sm" required>
              @foreach($bdUsers as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-xs-4">
            <input type="month" name="month" class="form-control input-sm" value="{{ now()->format('Y-m') }}" required>
          </div>
          <div class="col-xs-3">
            <input type="number" name="target_usd" class="form-control input-sm" placeholder="$USD" required min="0">
          </div>
        </div>
        <button type="submit" class="btn btn-default btn-sm btn-block" style="margin-top:6px;">Set Target</button>
      </form>
    </div>
  </div>

  {{-- ── Domains ──────────────────────────────────────────────────────────── --}}
  <div class="panel panel-bordered">
    <div class="panel-heading"><h3 class="panel-title">Domain Renewals</h3></div>
    <div class="panel-body" style="padding:0;">
      <table class="table table-condensed" style="margin:0;font-size:12px;">
        <thead><tr style="background:#f8fafc;">
          <th>Domain</th><th>Expires</th><th>Cost</th><th></th>
        </tr></thead>
        <tbody>
          @forelse($domains as $domain)
          @php
            $status = $domain->expiry_status;
            $colors = ['expired'=>'#dc2626','critical'=>'#f97316','warning'=>'#f59e0b','ok'=>'#4a5568'];
            $color = $colors[$status];
          @endphp
          <tr>
            <td>
              <strong>{{ $domain->name }}</strong><br>
              <small class="text-muted">{{ $domain->project }}</small>
            </td>
            <td>
              <span style="color:{{ $color }};font-weight:{{ $status !== 'ok' ? '700' : '400' }};">
                {{ $domain->expires_on->format('d M Y') }}
              </span><br>
              <small style="color:{{ $color }};">
                @if($status === 'expired') EXPIRED
                @else {{ $domain->days_until_expiry }}d left
                @endif
              </small>
            </td>
            <td>
              @if($domain->renewal_cost_pkr)
                Rs {{ number_format($domain->renewal_cost_pkr,0) }}
              @elseif($domain->renewal_cost_usd)
                ${{ $domain->renewal_cost_usd }}
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <button class="btn btn-xs btn-warning" onclick="showRenewForm({{ $domain->id }}, '{{ $domain->name }}')">Renew</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" class="text-center text-muted">No domains added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="panel-footer">
      <a href="#" data-toggle="collapse" data-target="#add-domain-form" style="font-size:12px;">
        + Add Domain
      </a>
      <div id="add-domain-form" class="collapse" style="margin-top:10px;">
        <form method="POST" action="{{ route('financials.store-domain') }}">
          @csrf
          <div class="row">
            <div class="col-xs-6"><input type="text" name="name" class="form-control input-sm" placeholder="e.g. webpenter.com" required></div>
            <div class="col-xs-6"><input type="date" name="expires_on" class="form-control input-sm" required></div>
          </div>
          <div class="row" style="margin-top:6px;">
            <div class="col-xs-4"><input type="text" name="project" class="form-control input-sm" placeholder="Project"></div>
            <div class="col-xs-4"><input type="number" name="renewal_cost_pkr" class="form-control input-sm" placeholder="Renewal PKR" step="1"></div>
            <div class="col-xs-4"><input type="number" name="renewal_cost_usd" class="form-control input-sm" placeholder="Renewal USD" step="0.01"></div>
          </div>
          <div class="row" style="margin-top:6px;">
            <div class="col-xs-8">
              <select name="paid_from" class="form-control input-sm">
                <option value="">Paid from…</option>
                @foreach(\App\Models\MonthlyExpense::$bankAccounts as $k => $v)
                  <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-sm btn-block">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>{{-- /col-md-5 --}}
</div>{{-- /row --}}

</div>

{{-- Renew domain modal --}}
<div class="modal fade" id="renew-modal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header"><h4 class="modal-title">Renew Domain</h4></div>
      <form method="POST" id="renew-form">
        @csrf @method('PATCH')
        <div class="modal-body">
          <p id="renew-domain-name" style="font-weight:700;"></p>
          <div class="form-group">
            <label>New expiry date</label>
            <input type="date" name="renewed_expires_on" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Renewal cost (PKR)</label>
            <input type="number" name="renewal_cost_pkr" class="form-control" placeholder="Leave blank to skip expense entry">
          </div>
          <div class="form-group">
            <label>Month to charge</label>
            <input type="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
          </div>
          <div class="form-group">
            <label>Paid from</label>
            <select name="paid_from" class="form-control">
              <option value="">— not specified —</option>
              @foreach(\App\Models\MonthlyExpense::$bankAccounts as $k => $v)
                <option value="{{ $k }}">{{ $v }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Renew</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('javascript')
<script>
function showRenewForm(domainId, domainName) {
    document.getElementById('renew-domain-name').textContent = domainName;
    document.getElementById('renew-form').action = '/admin/financials/domains/' + domainId + '/renew';
    $('#renew-modal').modal('show');
}
</script>
@stop
