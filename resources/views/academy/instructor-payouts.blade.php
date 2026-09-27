@extends('voyager::master')

@section('page_title', 'Instructor Payouts')

@section('page_header')
    <h1 class="page-title"><i class="voyager-wallet"></i> Instructor Payouts</h1>
@stop

@section('content')
<div class="page-content browse container-fluid">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <p class="text-muted">Commission is calculated and attributed automatically when a student's fee is marked
                paid - that's not the same as the instructor actually having received the money. This is where you
                pay them and record it, plus any advance, bonus, or one-off adjustment not tied to a specific
                student's fee.</p>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('academy.instructor-payouts.index') }}" style="margin-bottom:18px; display:flex; gap:10px; align-items:center;">
                <select name="instructor_id" class="form-control" style="width:auto;">
                    <option value="">All instructors</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected($instructorId == $instructor->id)>{{ $instructor->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if($instructorId)
                    <a href="{{ route('academy.instructor-payouts.index') }}" class="btn btn-default btn-sm">Reset</a>
                @endif
            </form>

            <div style="background:#fef3c7; border:1px solid #fde68a; border-radius:10px; padding:14px 18px; margin-bottom:20px; max-width:280px;">
                <div style="font-size:11px; color:#92400e; font-weight:700; text-transform:uppercase;">Total Owed (Commission)</div>
                <div style="font-size:22px; font-weight:800; color:#0f172a;">Rs. {{ number_format($totalPending, 2) }}</div>
            </div>

            <h4>Pending Commission Payout ({{ $pending->count() }})</h4>
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Instructor</th><th>Student</th><th>Track</th><th>Month</th><th>Commission</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($pending as $invoice)
                        <tr>
                            <td>{{ $invoice->enrollment->instructor->name ?? '-' }}</td>
                            <td>{{ $invoice->enrollment->user->name }}</td>
                            <td>{{ $invoice->enrollment->track->name }}</td>
                            <td>{{ $invoice->month->format('F Y') }}</td>
                            <td>Rs. {{ number_format($invoice->instructor_commission_amount, 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('academy.instructor-payouts.mark-paid', $invoice) }}" enctype="multipart/form-data" style="display:flex; gap:6px; align-items:center;">
                                    @csrf
                                    <input type="file" name="proof" style="max-width:160px; font-size:11px;" title="Optional: attach a payment receipt/screenshot">
                                    <button type="submit" class="btn btn-success btn-xs">Mark Paid</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted">Nothing owed right now.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <h4 style="margin-top:30px;">Record an Advance, Bonus, or Adjustment</h4>
            <p class="text-muted" style="margin-top:-6px;">For a payment that isn't tied to a specific student's fee - e.g. an advance against future commission.</p>
            <form method="POST" action="{{ route('academy.instructor-payouts.store-manual') }}" enctype="multipart/form-data" style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-start; margin-bottom:30px;">
                @csrf
                <select name="instructor_id" class="form-control" style="width:auto;" required>
                    <option value="">Select instructor...</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="amount" class="form-control" placeholder="Amount (Rs.)" step="0.01" min="1" style="width:140px;" required>
                <select name="type" class="form-control" style="width:auto;">
                    <option value="advance">Advance</option>
                    <option value="bonus">Bonus</option>
                    <option value="adjustment">Adjustment</option>
                </select>
                <input type="text" name="note" class="form-control" placeholder="Note (optional)" style="width:220px;">
                <input type="file" name="proof" style="max-width:160px; font-size:11px;" title="Optional: attach a receipt/screenshot">
                <button type="submit" class="btn btn-success btn-sm">Record Payment</button>
            </form>

            <h4>Payout History</h4>
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Type</th><th>Instructor</th><th>Detail</th><th>Amount</th><th>Paid On</th><th>Paid By</th><th>Proof</th></tr>
                </thead>
                <tbody>
                    @forelse($history as $row)
                        <tr>
                            <td><span class="label {{ $row['type'] === 'Commission' ? 'label-success' : 'label-info' }}">{{ $row['type'] }}</span></td>
                            <td>{{ $row['instructor']->name ?? '-' }}</td>
                            <td>{{ $row['detail'] }}</td>
                            <td>Rs. {{ number_format($row['amount'], 2) }}</td>
                            <td>{{ $row['paid_at']?->format('d M Y') }}</td>
                            <td>{{ $row['paid_by']->name ?? '-' }}</td>
                            <td>
                                @if($row['proof_path'])
                                    <a href="{{ asset('storage/' . $row['proof_path']) }}" target="_blank">View</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted">No payouts recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@stop
