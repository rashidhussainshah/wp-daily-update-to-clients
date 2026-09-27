@extends('voyager::master')

@section('page_title', 'Fees & Invoices')

@section('page_header')
    <h1 class="page-title"><i class="voyager-credit-cards"></i> Academy Fees &amp; Invoices</h1>
@stop

@section('content')
<div class="page-content browse container-fluid">
    <div class="panel panel-bordered">
        <div class="panel-body">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('academy.fees.index') }}" class="form-inline" style="margin-bottom:18px; display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                <input type="text" name="student" class="form-control" placeholder="Search student..." value="{{ $filters['student'] ?? '' }}">

                <select name="status" class="form-control">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                    <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Paid</option>
                    <option value="overdue" @selected(($filters['status'] ?? '') === 'overdue')>Overdue</option>
                </select>

                <select name="track_id" class="form-control">
                    <option value="">All tracks</option>
                    @foreach($tracks as $track)
                        <option value="{{ $track->id }}" @selected(($filters['track_id'] ?? '') == $track->id)>{{ $track->name }}</option>
                    @endforeach
                </select>

                <input type="month" name="month" class="form-control" value="{{ !empty($filters['month']) ? \Illuminate\Support\Carbon::parse($filters['month'])->format('Y-m') : '' }}">

                <label style="margin:0; font-weight:normal;">
                    <input type="checkbox" name="needs_attention" value="1" @checked($filters['needs_attention'] ?? false)>
                    Needs attention (proof submitted)
                </label>

                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('academy.fees.index') }}" class="btn btn-default btn-sm">Reset</a>
            </form>

            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Student</th><th>Track</th><th>Month</th><th>Amount</th><th>Status</th><th>Proof</th><th>Instructor</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr @if($invoice->payment_proof_submitted_at && $invoice->status !== 'paid') style="background:#f0fdf4;" @endif>
                            <td>{{ $invoice->enrollment->user->name }}</td>
                            <td>{{ $invoice->enrollment->track->name }}</td>
                            <td>{{ $invoice->month->format('F Y') }}</td>
                            <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="label label-success">Paid</span>
                                @else
                                    <span class="label label-warning">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($invoice->payment_proof_path)
                                    <a href="{{ asset('storage/' . $invoice->payment_proof_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $invoice->payment_proof_path) }}" style="width:40px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #dcfce7;">
                                    </a>
                                    <div style="font-size:10px; color:#94a3b8;">{{ $invoice->payment_proof_submitted_at->diffForHumans() }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($invoice->instructor_commission_credited)
                                    {{ $invoice->enrollment->instructor->name ?? '-' }} &middot; Rs. {{ number_format($invoice->instructor_commission_amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($invoice->status !== 'paid')
                                    <form method="POST" action="{{ route('academy.fees.mark-paid', $invoice) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-xs">Mark as Paid</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@stop
