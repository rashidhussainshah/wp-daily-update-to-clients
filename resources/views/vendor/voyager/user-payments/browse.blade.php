@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')

        @if(!is_null($advancePkr))
            <div class="alert alert-info" style="padding:8px 14px;">
                Advance (Credit To Dev): <strong>{{ number_format($advancePkr, 2) }} PKR</strong>
                @if($advanceUsd) + <strong>${{ number_format($advanceUsd, 2) }}</strong> @endif
                &middot; Remaining after advance &amp; paid: <strong>{{ number_format(($totals->payable - $advancePkr) - $totals->paid, 2) }} PKR</strong>
            </div>
        @endif

        {{-- Filters --}}
        <div class="panel panel-bordered">
            <div class="panel-body">
                @include('voyager::user-payments._date_presets')
                <form method="GET" action="{{ route('voyager.user-payments.index') }}" class="form-inline" id="paymentFilters">
                    @if($canManage)
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
                    @endif
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
                    <button type="submit" class="btn btn-primary btn-sm" style="margin:4px;"><i class="voyager-search"></i> Filter</button>
                    <a href="{{ route('voyager.user-payments.index') }}" class="btn btn-default btn-sm" style="margin:4px;">Reset</a>
                    @if(isAdministrator())
                        <a href="{{ route('user-payments.statistics', request()->query()) }}" class="btn btn-warning btn-sm" style="margin:4px;">
                            <i class="voyager-bar-chart"></i> Statistics
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Listing --}}
        <div class="panel panel-bordered">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Project / Target</th>
                        <th>Income</th>
                        <th>Source</th>
                        <th class="text-right">Total $</th>
                        <th class="text-right">Share $</th>
                        <th class="text-right">Rate</th>
                        <th class="text-right">Payable PKR</th>
                        <th class="text-right">Paid PKR</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        @php
                            $child = $grouped ? $payment->linkedCommission : null;
                            $pairWarning = null;
                            if ($child) {
                                if ($payment->paid && !$child->paid) {
                                    $pairWarning = "Linked BD commission #{$child->id} not paid yet";
                                } elseif (!$payment->paid && $child->paid) {
                                    $pairWarning = "Partner request #{$payment->id} not paid, but its commission is";
                                } elseif ($payment->status === 'Approved' && $child->status === 'Requested') {
                                    $pairWarning = "Linked BD commission #{$child->id} still awaiting approval";
                                }
                            }
                        @endphp
                        <tr id="payment-{{ $payment->id }}">
                            <td>{{ $payment->id }}
                                @if($payment->generated_by_system)
                                    <i class="voyager-settings" title="System generated"></i>
                                @endif
                                @if($pairWarning)
                                    <i class="voyager-warning text-warning" data-toggle="tooltip" title="{{ $pairWarning }}"></i>
                                @endif
                                @if(!$grouped && $payment->second_entry_id)
                                    <br><small class="text-muted">&#8599; from #{{ $payment->second_entry_id }}</small>
                                @endif
                            </td>
                            <td>{{ $payment->developer->name ?? '-' }}</td>
                            <td>
                                @if($payment->share_type === 'business_developer')
                                    <span class="label label-info">BD</span>
                                @else
                                    <span class="label label-primary">Partner</span>
                                @endif
                            </td>
                            <td>
                                {{ $payment->project->name ?? '-' }}
                                <br><small class="text-muted">{{ $payment->projectTarget->title ?? '' }}</small>
                            </td>
                            <td>
                                @if($payment->income)
                                    <a href="javascript:void(0)" class="income-popover" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="right"
                                       data-content="Amount: <strong>{{ $payment->income->amount }} {{ strtoupper($payment->income->amount_in ?? 'usd') }}</strong><br>Source: {{ ucfirst($payment->income->source ?? '-') }}<br>Date: {{ $payment->income->transaction_date ?? $payment->income->created_at?->format('Y-m-d') ?? '-' }}{{ $payment->income->note ? '<br>Note: ' . e(\Illuminate\Support\Str::limit($payment->income->note, 120)) : '' }}">
                                        #{{ $payment->income_id }}</a>
                                @else
                                    #{{ $payment->income_id }}
                                @endif
                            </td>
                            <td>{{ ucfirst($payment->client_source ?? '-') }}</td>
                            <td class="text-right">{{ number_format($payment->total_earning, 2) }}</td>
                            <td class="text-right">{{ number_format($payment->dev_earning, 2) }}</td>
                            <td class="text-right">{{ $payment->currency_current_rate ?: '-' }}</td>
                            <td class="text-right">{{ $payment->payable ? number_format($payment->payable, 2) : '-' }}</td>
                            <td class="text-right">{{ $payment->paid ? number_format($payment->paid, 2) : '-' }}</td>
                            <td>
                                @if($payment->paid)
                                    <span class="label label-success">Paid</span>
                                @elseif($payment->status === 'Approved')
                                    <span class="label label-warning">Approved</span>
                                @else
                                    <span class="label label-default">Requested</span>
                                @endif
                            </td>
                            <td><small>{{ $payment->created_at?->format('d M Y') }}</small></td>
                            <td class="text-right" style="white-space:nowrap;">
                                @if($canManage && $payment->status === 'Requested')
                                    <button type="button" class="btn btn-success btn-xs rate-approve-btn"
                                            data-id="{{ $payment->id }}"
                                            data-name="{{ $payment->developer->name ?? '' }}"
                                            data-share="{{ $payment->dev_earning }}"
                                            title="Set PKR rate &amp; approve">
                                        <i class="voyager-check"></i> Rate &amp; Approve
                                    </button>
                                @endif
                                @if($canPay && $payment->status === 'Approved' && is_null($payment->paid) && $payment->payable)
                                    <a href="{{ route('mark-user-payment-paid', $payment->id) }}"
                                       class="btn btn-info btn-xs"
                                       onclick="return confirm('Mark payment #{{ $payment->id }} ({{ number_format($payment->payable, 2) }} PKR) as paid?')">
                                        <i class="voyager-dollar"></i> Mark Paid
                                    </a>
                                @endif
                                @can('edit', $payment)
                                    <a href="{{ route('voyager.user-payments.edit', $payment->id) }}" class="btn btn-primary btn-xs" title="{{ __('voyager::generic.edit') }}">
                                        <i class="voyager-edit"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        @if($child)
                            <tr id="payment-{{ $child->id }}" style="background:#f4f8fd;border-left:4px solid #5bc0de;">
                                <td style="padding-left:22px;border-left:4px solid #5bc0de;">
                                    <i class="voyager-forward text-info"></i> {{ $child->id }}
                                    @if($pairWarning)
                                        <i class="voyager-warning text-warning" data-toggle="tooltip" title="{{ $pairWarning }}"></i>
                                    @endif
                                </td>
                                <td>{{ $child->developer->name ?? '-' }}</td>
                                <td><span class="label label-info">BD</span></td>
                                <td>
                                    {{ $payment->project->name ?? '-' }}
                                    <br><small class="text-muted">{{ $payment->projectTarget->title ?? '' }}</small>
                                </td>
                                <td>
                                    @if($child->income)
                                        <a href="javascript:void(0)" class="income-popover" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="right"
                                           data-content="Amount: <strong>{{ $child->income->amount }} {{ strtoupper($child->income->amount_in ?? 'usd') }}</strong><br>Source: {{ ucfirst($child->income->source ?? '-') }}<br>Date: {{ $child->income->transaction_date ?? $child->income->created_at?->format('Y-m-d') ?? '-' }}{{ $child->income->note ? '<br>Note: ' . e(\Illuminate\Support\Str::limit($child->income->note, 120)) : '' }}">
                                            #{{ $child->income_id }}</a>
                                    @else
                                        #{{ $child->income_id }}
                                    @endif
                                </td>
                                <td>{{ ucfirst($child->client_source ?? '-') }}</td>
                                <td class="text-right text-muted">&mdash;</td>
                                <td class="text-right">{{ number_format($child->dev_earning, 2) }}</td>
                                <td class="text-right">{{ $child->currency_current_rate ?: '-' }}</td>
                                <td class="text-right">{{ $child->payable ? number_format($child->payable, 2) : '-' }}</td>
                                <td class="text-right">{{ $child->paid ? number_format($child->paid, 2) : '-' }}</td>
                                <td>
                                    @if($child->paid)
                                        <span class="label label-success">Paid</span>
                                    @elseif($child->status === 'Approved')
                                        <span class="label label-warning">Approved</span>
                                    @else
                                        <span class="label label-default">Requested</span>
                                    @endif
                                </td>
                                <td><small>{{ $child->created_at?->format('d M Y') }}</small></td>
                                <td class="text-right" style="white-space:nowrap;">
                                    @if($canManage && $child->status === 'Requested')
                                        <button type="button" class="btn btn-success btn-xs rate-approve-btn"
                                                data-id="{{ $child->id }}"
                                                data-name="{{ $child->developer->name ?? '' }}"
                                                data-share="{{ $child->dev_earning }}"
                                                title="Set PKR rate &amp; approve">
                                            <i class="voyager-check"></i> Rate &amp; Approve
                                        </button>
                                    @endif
                                    @if($canPay && $child->status === 'Approved' && is_null($child->paid) && $child->payable)
                                        <a href="{{ route('mark-user-payment-paid', $child->id) }}"
                                           class="btn btn-info btn-xs"
                                           onclick="return confirm('Mark payment #{{ $child->id }} ({{ number_format($child->payable, 2) }} PKR) as paid?')">
                                            <i class="voyager-dollar"></i> Mark Paid
                                        </a>
                                    @endif
                                    @can('edit', $child)
                                        <a href="{{ route('voyager.user-payments.edit', $child->id) }}" class="btn btn-primary btn-xs" title="{{ __('voyager::generic.edit') }}">
                                            <i class="voyager-edit"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">No payment requests found for the selected filters.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">
                    <small>Showing {{ $payments->firstItem() ?? 0 }}-{{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }}{{ $grouped ? ' (linked BD commissions shown under their requests)' : '' }}</small>
                </div>
                <div class="pull-right">
                    {{ $payments->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Rate & Approve modal --}}
    <div class="modal fade" id="rateApproveModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="POST" id="rateApproveForm" action="">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Approve payment <span id="rateApproveTitle"></span></h4>
                    </div>
                    <div class="modal-body">
                        <p>Share: $<span id="rateApproveShare"></span></p>
                        <div class="form-group">
                            <label for="rateInput">USD to PKR rate</label>
                            <input type="number" step="0.01" min="1" name="currency_current_rate" id="rateInput" class="form-control" placeholder="e.g. 277" required>
                        </div>
                        <p class="text-muted">Payable: <strong id="rateApprovePayable">-</strong> PKR<br>
                            <small>The linked business developer commission (if any) will be approved at the same rate.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(function () {
            $('.income-popover').popover({ container: 'body' });

            var rateApproveUrl = '{{ url('admin/user-payments') }}/__ID__/rate-approve';
            var currentShare = 0;

            $('.rate-approve-btn').on('click', function () {
                var id = $(this).data('id');
                currentShare = parseFloat($(this).data('share')) || 0;
                $('#rateApproveTitle').text('#' + id + ' - ' + $(this).data('name'));
                $('#rateApproveShare').text(currentShare.toFixed(2));
                $('#rateApproveForm').attr('action', rateApproveUrl.replace('__ID__', id));
                $('#rateInput').val('');
                $('#rateApprovePayable').text('-');
                $('#rateApproveModal').modal('show');
            });

            $('#rateInput').on('input', function () {
                var rate = parseFloat($(this).val());
                $('#rateApprovePayable').text(!isNaN(rate) ? (currentShare * rate).toFixed(2) : '-');
            });
        });
    </script>
@stop
