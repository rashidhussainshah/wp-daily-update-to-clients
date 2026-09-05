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

            <table class="table">
                <thead>
                    <tr><th>Student</th><th>Track</th><th>Month</th><th>Amount</th><th>Status</th><th>Instructor</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
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
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@stop
