@extends('voyager::master')

@section('page_title', 'Expense Detail')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-dollar"></i> {{ $expense->category_label }} <small class="text-muted">— {{ $expense->month }}</small></h1>
    <a href="{{ route('financials.index') }}" class="btn btn-default btn-sm">
        <i class="voyager-arrow-left"></i> Back to Financials
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

@if($expense->parent_expense_id)
{{-- This row is itself a payment toward another bill --}}
<div class="alert alert-info">
    This is a payment of <strong>Rs {{ number_format($expense->amount_pkr,0) }}</strong> toward
    <a href="{{ route('financials.show-expense', $expense->parentExpense->id) }}">{{ $expense->parentExpense->category_label }} ({{ $expense->parentExpense->month }})</a>.
</div>
@endif

<div class="row">
@if($expense->isBill())
  <div class="col-md-4 col-sm-4">
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Expected</div>
      <div style="font-size:20px;font-weight:700;color:#fff;margin-top:4px;">Rs {{ number_format($expense->expected_amount_pkr,0) }}</div>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="panel" style="background:#064e3b;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Paid So Far</div>
      <div style="font-size:20px;font-weight:700;color:#34d399;margin-top:4px;">Rs {{ number_format($expense->paid_amount,0) }}</div>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="panel" style="background:{{ $expense->pending_amount > 0 ? '#7f1d1d' : '#064e3b' }};border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Pending</div>
      <div style="font-size:20px;font-weight:700;color:{{ $expense->pending_amount > 0 ? '#f87171' : '#34d399' }};margin-top:4px;">
        Rs {{ number_format($expense->pending_amount,0) }}
      </div>
      <div style="font-size:11px;color:rgba(255,255,255,0.5);">{{ $expense->pending_amount > 0 ? 'Not fully settled' : 'Fully settled' }}</div>
    </div>
  </div>
@else
  <div class="col-md-4 col-sm-4">
    <div class="panel" style="background:#1a1a2e;border:none;border-radius:8px;padding:18px 20px;">
      <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;">Amount</div>
      <div style="font-size:20px;font-weight:700;color:#fff;margin-top:4px;">Rs {{ number_format($expense->amount_pkr,0) }}</div>
    </div>
  </div>
@endif
</div>

<div class="row" style="margin-top:16px;">
  <div class="col-md-7">

    @if($expense->isBill())
    <div class="panel panel-bordered">
      <div class="panel-heading"><h3 class="panel-title">Payments Against This Bill</h3></div>
      <div class="panel-body" style="padding:0;">
        <table class="table table-condensed" style="margin:0;">
          <thead><tr style="background:#f8fafc;">
            <th>Date</th><th>Paid From</th><th>Note</th><th class="text-right">Amount</th><th></th>
          </tr></thead>
          <tbody>
            @forelse($expense->payments as $payment)
            <tr>
              <td>{{ $payment->created_at->format('d M Y') }}</td>
              <td>{{ \App\Models\MonthlyExpense::$bankAccounts[$payment->paid_from] ?? '—' }}</td>
              <td>
                {{ $payment->note ?: '—' }}
                @if($payment->is_advance) <span class="label label-info" style="font-size:9px;">advance</span> @endif
              </td>
              <td class="text-right">Rs {{ number_format($payment->amount_pkr,0) }}</td>
              <td>
                @if($payment->attachments)
                  @foreach($payment->attachments as $path)
                    <a href="{{ Illuminate\Support\Facades\Storage::disk(config('voyager.storage.disk'))->url($path) }}" target="_blank" title="View attachment"><i class="voyager-attachment"></i></a>
                  @endforeach
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">No payments recorded yet.</td></tr>
            @endforelse
          </tbody>
          <tfoot><tr style="background:#f0fdf4;font-weight:700;">
            <td colspan="3">TOTAL PAID</td>
            <td class="text-right">Rs {{ number_format($expense->paid_amount,0) }}</td>
            <td></td>
          </tr></tfoot>
        </table>
      </div>
    </div>
    @endif

    @if($expense->attachments)
    <div class="panel panel-bordered">
      <div class="panel-heading"><h3 class="panel-title">Attachments</h3></div>
      <div class="panel-body">
        @foreach($expense->attachments as $path)
          <a href="{{ Illuminate\Support\Facades\Storage::disk(config('voyager.storage.disk'))->url($path) }}" target="_blank" class="btn btn-xs btn-default" style="margin-bottom:4px;">
            <i class="voyager-attachment"></i> {{ basename($path) }}
          </a><br>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  <div class="col-md-5">
    @if($expense->isBill() && $expense->pending_amount > 0)
    <div class="panel panel-bordered">
      <div class="panel-heading"><h3 class="panel-title">Record a Payment</h3></div>
      <div class="panel-body">
        <form method="POST" action="{{ route('financials.store-expense') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="parent_expense_id" value="{{ $expense->id }}">
          <input type="hidden" name="month" value="{{ $expense->month }}">
          <input type="hidden" name="category" value="{{ $expense->category }}">
          <div class="form-group" style="margin-bottom:8px;">
            <label style="font-size:11px;">Amount Paid (PKR)</label>
            <input type="number" name="amount_pkr" class="form-control input-sm" placeholder="e.g. 5000" required min="0" step="1">
          </div>
          <div class="form-group" style="margin-bottom:8px;">
            <label style="font-size:11px;">Paid From</label>
            <select name="paid_from" class="form-control input-sm">
              <option value="">— not specified —</option>
              @foreach(\App\Models\MonthlyExpense::$bankAccounts as $k => $v)
                <option value="{{ $k }}">{{ $v }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group" style="margin-bottom:8px;">
            <label style="font-size:11px;">Note (optional)</label>
            <input type="text" name="note" class="form-control input-sm" placeholder="e.g. partial payment">
          </div>
          <div class="form-group" style="margin-bottom:8px;">
            <label style="font-size:11px;">Attachment (optional)</label>
            <input type="file" name="attachments[]" class="form-control input-sm" accept="image/*,.pdf" multiple>
          </div>
          <button type="submit" class="btn btn-primary btn-sm btn-block">Add Payment</button>
        </form>
      </div>
    </div>
    @endif

    <div class="panel panel-bordered">
      <div class="panel-heading"><h3 class="panel-title">Details</h3></div>
      <div class="panel-body">
        <table class="table table-condensed" style="margin:0;">
          <tr><td class="text-muted">Category</td><td>{{ $expense->category_label }}</td></tr>
          <tr><td class="text-muted">Month</td><td>{{ $expense->month }}</td></tr>
          <tr><td class="text-muted">Fixed?</td><td>{{ $expense->is_fixed ? 'Yes' : 'No' }}</td></tr>
          <tr><td class="text-muted">Advance?</td><td>{{ $expense->is_advance ? 'Yes' : 'No' }}</td></tr>
          <tr><td class="text-muted">Created By</td><td>{{ $expense->creator?->name ?? '—' }}</td></tr>
          <tr><td class="text-muted">Created At</td><td>{{ $expense->created_at->format('d M Y, h:i A') }}</td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

</div>
@stop
