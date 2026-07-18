@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Income #'.$dataTypeContent->id)

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> Income #{{ $dataTypeContent->id }}
        <a href="{{ route('voyager.incomes.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> {{ __('voyager::generic.return_to_list') }}
        </a>
        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.incomes.edit', $dataTypeContent->id) }}" class="btn btn-primary">
                <i class="voyager-edit"></i> {{ __('voyager::generic.edit') }}
            </a>
        @endcan
        @can('add', $dataTypeContent)
            <a href="{{ route('voyager.incomes.create') }}" class="btn btn-success">
                <i class="voyager-plus"></i> Add New Income
            </a>
        @endcan
    </h1>
@stop

@section('content')
    @php
        $payments = $dataTypeContent->payments()
            ->with(['developer' => fn($q) => $q->withoutGlobalScopes()->withTrashed(), 'project'])
            ->orderBy('id')
            ->get();
        $partnerTaken = $payments->where('share_type', \App\Models\UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER)->sum('dev_earning');
        $bdTaken = $payments->where('share_type', \App\Models\UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)->sum('dev_earning');
        $attachments = json_decode($dataTypeContent->attachments ?? '[]', true) ?: [];
    @endphp

    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="panel-heading"><h3 class="panel-title">Income detail</h3></div>
                    <div class="panel-body">
                        <table class="table table-condensed" style="margin-bottom:0;">
                            <tr><th style="width:45%;">Order Date</th><td>{{ $dataTypeContent->transaction_date ? \Carbon\Carbon::parse($dataTypeContent->transaction_date)->format('d M Y') : '-' }}</td></tr>
                            <tr><th>Amount</th><td><strong>${{ number_format($dataTypeContent->amount, 2) }}</strong> {{ strtoupper($dataTypeContent->amount_in ?? 'USD') }}</td></tr>
                            <tr><th>Source</th><td><span class="label label-info">{{ ucfirst(str_replace('_', ' ', $dataTypeContent->source)) }}</span></td></tr>
                            <tr><th>Received In</th><td>{{ $dataTypeContent->received_in ? ucwords(str_replace('_', ' ', $dataTypeContent->received_in)) : '-' }}</td></tr>
                            <tr><th>Conversion Rate</th><td>{{ $dataTypeContent->conversion_rate ? number_format($dataTypeContent->conversion_rate, 2) : '-' }}</td></tr>
                            <tr><th>Converted PKR</th><td>{{ $dataTypeContent->converted_pkr ? number_format($dataTypeContent->converted_pkr, 2).' PKR' : '-' }}</td></tr>
                            <tr><th>Visible to team</th><td>{{ $dataTypeContent->show_to_dev === 'yes' ? 'Yes' : 'No' }}</td></tr>
                            <tr><th>Created</th><td>{{ $dataTypeContent->created_at?->format('d M Y H:i') }}</td></tr>
                            @if($dataTypeContent->note)
                                <tr><th>Note</th><td>{{ $dataTypeContent->note }}</td></tr>
                            @endif
                        </table>
                        @if($attachments)
                            <hr style="margin:10px 0;">
                            <strong>Attachments</strong><br>
                            @foreach($attachments as $file)
                                @if(\Illuminate\Support\Str::endsWith(strtolower($file), '.pdf'))
                                    <a href="{{ Voyager::image($file) }}" target="_blank" class="btn btn-xs btn-default" style="margin-top:5px;">
                                        <i class="voyager-file-text"></i> {{ basename($file) }}
                                    </a>
                                @else
                                    <a href="{{ Voyager::image($file) }}" target="_blank">
                                        <img src="{{ Voyager::image($file) }}" style="width:70px;margin:5px 5px 0 0;border:1px solid #ddd;border-radius:3px;">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Payment requests from this income ({{ $payments->count() }})</h3>
                    </div>
                    <div class="panel-body">
                        @if($payments->isEmpty())
                            <p class="text-muted">No payment requests have been made against this income yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Person</th>
                                            <th>Type</th>
                                            <th>Project</th>
                                            <th class="text-right">Earning $</th>
                                            <th class="text-right">Share $</th>
                                            <th class="text-right">Payable PKR</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $p)
                                            <tr>
                                                <td><a href="{{ route('voyager.user-payments.index', ['ids' => $p->id]) }}">{{ $p->id }}</a></td>
                                                <td>{{ $p->developer->name ?? '#'.$p->developer_id }}</td>
                                                <td>
                                                    @if($p->share_type === \App\Models\UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER)
                                                        <span class="label label-primary">BD</span>
                                                    @else
                                                        <span class="label label-success">Partner</span>
                                                    @endif
                                                    @if($p->generated_by_system)
                                                        <span class="label" style="background:#8492a6;">Auto</span>
                                                    @endif
                                                </td>
                                                <td><small>{{ $p->project->name ?? '-' }}</small></td>
                                                <td class="text-right">{{ number_format($p->total_earning, 2) }}</td>
                                                <td class="text-right"><strong>{{ number_format($p->dev_earning, 2) }}</strong></td>
                                                <td class="text-right">{{ $p->payable ? number_format($p->payable, 2) : '-' }}</td>
                                                <td>
                                                    @if(!is_null($p->paid))
                                                        <span class="label label-success">Paid</span>
                                                    @elseif($p->status === 'Approved')
                                                        <span class="label label-warning">Approved</span>
                                                    @else
                                                        <span class="label label-default">Requested</span>
                                                    @endif
                                                </td>
                                                <td><small>{{ $p->created_at?->format('d M Y') }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#f8f9fa;">
                                            <th colspan="5" class="text-right">Development partner share taken</th>
                                            <th class="text-right">${{ number_format($partnerTaken, 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                        <tr style="background:#f8f9fa;">
                                            <th colspan="5" class="text-right">BD commission taken</th>
                                            <th class="text-right">${{ number_format($bdTaken, 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                        <tr style="background:#f4f8fd;">
                                            <th colspan="5" class="text-right">Total shares from this income (of ${{ number_format($dataTypeContent->amount, 2) }})</th>
                                            <th class="text-right">${{ number_format($partnerTaken + $bdTaken, 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
