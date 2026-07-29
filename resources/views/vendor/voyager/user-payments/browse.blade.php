@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('css')
    <style>
        /* Outline each partner request + linked BD commission as one group */
        .pair-parent > td {
            border-top: 2px solid #5bc0de !important;
            background: #fbfdff;
        }
        .pair-child > td {
            border-bottom: 2px solid #5bc0de !important;
            background: #f4f8fd;
        }
        .pair-parent > td:first-child,
        .pair-child > td:first-child {
            border-left: 3px solid #5bc0de;
        }
        .pair-parent > td:last-child,
        .pair-child > td:last-child {
            border-right: 3px solid #5bc0de;
        }
        /* No divider line inside the group */
        .pair-child > td {
            border-top: none !important;
        }

        /* Listing readability: strong header, dark cell text */
        .browse .table > thead > tr > th {
            background: #2a3542;
            color: #ffffff !important;
            border-bottom: 2px solid #1f2833;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .3px;
            white-space: nowrap;
        }
        .browse .table > tbody > tr > td {
            color: #16202b !important;
            font-size: 13.5px;
        }
        .browse .table td small,
        .browse .table td .text-muted {
            color: #4a5561 !important;
        }

        /* Attachment slider inside the detail modal (self-contained -
           does not rely on the Bootstrap carousel plugin or CSS) */
        .dc-carousel { position: relative; padding-bottom: 22px; }
        .dc-carousel .dc-item { display: none; text-align: center; }
        .dc-carousel .dc-item.active { display: block; }
        .dc-carousel .dc-item img {
            margin: 0 auto;
            max-height: 420px;
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .dc-carousel .dc-control {
            position: absolute;
            top: 45%;
            font-size: 30px;
            line-height: 1;
            color: #2a3542;
            background: rgba(255,255,255,.85);
            border: 1px solid #cfd6dd;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            text-align: center;
            padding-top: 3px;
            cursor: pointer;
            text-decoration: none;
            z-index: 5;
        }
        .dc-carousel .dc-control:hover { background: #fff; color: #000; }
        .dc-carousel .dc-control.dc-prev { left: 6px; }
        .dc-carousel .dc-control.dc-next { right: 6px; }
        .dc-carousel .dc-indicators {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .dc-carousel .dc-indicators li {
            display: inline-block;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 1px solid #55606c;
            margin: 0 3px;
            cursor: pointer;
        }
        .dc-carousel .dc-indicators li.active { background-color: #55606c; }

        /* Mobile responsiveness - desktop layout above is untouched */
        @media (max-width: 767px) {
            /* Page header buttons: stop floating/squeezing next to the title */
            .page-title .btn-add-new {
                float: none;
                display: block;
                width: 100%;
                margin: 6px 0 0 0;
            }

            /* Filter form: one full-width control per row instead of a
               cramped inline row of fixed-width pills */
            #paymentFilters.form-inline .form-group {
                display: block;
                width: 100% !important;
                margin: 0 0 8px 0 !important;
            }
            #paymentFilters.form-inline .form-group .form-control,
            #paymentFilters.form-inline .form-group select,
            #paymentFilters.form-inline .form-group input,
            #paymentFilters .select2-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            #paymentFilters > .btn,
            #paymentFilters > a.btn {
                display: block;
                width: 100%;
                margin: 4px 0 !important;
            }

            /* Listing table: smaller text/padding, and every cell kept on one
               line (including the Actions buttons) so the table is forced
               wider than the screen - that's what makes table-responsive's
               horizontal scroll actually kick in. Letting cells wrap instead
               shrinks the table back down to fit the screen, which leaves
               nothing to scroll to and hides the action buttons. */
            .browse .table > thead > tr > th,
            .browse .table > tbody > tr > td {
                font-size: 11.5px;
                padding: 6px;
                white-space: nowrap;
            }
            .browse .table {
                min-width: 1100px;
            }
            .browse .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .browse .table td:last-child {
                min-width: 150px;
            }
            .browse .table .btn-xs {
                margin: 0 2px;
            }

            /* Modal footers with a left + right button: stack instead of
               overlapping on narrow screens */
            .modal-footer .pull-left {
                float: none !important;
                display: block;
                width: 100%;
                margin-bottom: 8px;
            }
            .modal-footer .btn {
                width: 100%;
                margin: 4px 0 !important;
            }

            /* Result count + pagination footer: stack instead of overlapping */
            .panel-footer.clearfix .pull-left,
            .panel-footer.clearfix .pull-right {
                float: none !important;
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 8px;
            }
        }
    </style>
@stop

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
        <a href="{{ route('user-payments.flow-guide') }}" class="btn btn-default btn-add-new">
            <i class="voyager-info-circled"></i> <span>How it works</span>
        </a>
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
                    <div class="form-group" style="margin:4px;">
                        <input type="text" name="ids" class="form-control input-sm" style="width:160px;"
                               placeholder="ID(s) e.g. 341, 350" value="{{ $filters['ids'] ?? '' }}"
                               title="Search one or more request IDs, separated by comma or space">
                    </div>
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
                            <option value="development_partner" @selected(($filters['share_type'] ?? '') == 'development_partner')>Development Partner</option>
                            <option value="business_developer" @selected(($filters['share_type'] ?? '') == 'business_developer')>Business Developer</option>
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
                    @if(isAdministrator())
                        <div class="form-group" style="margin:4px;">
                            <select name="mismatch" class="form-control input-sm" title="Find calculation errors. Combine with the User filter above to check one person. The 35%/37.5% checks are development partners only.">
                                <option value="">Calculation check: all</option>
                                <option value="1" @selected(($filters['mismatch'] ?? '') === '1')>PKR mismatch (share &times; rate &ne; payable)</option>
                                <option value="35" @selected(($filters['mismatch'] ?? '') === '35')>Share mismatch (35%)</option>
                                <option value="37.5" @selected(($filters['mismatch'] ?? '') === '37.5')>Share mismatch (37.5%)</option>
                            </select>
                        </div>
                    @endif
                    @if(isAdministrator())
                        <div class="form-group" style="margin:4px;">
                            <select name="income_id" id="incomeFilter" class="form-control input-sm" style="width:260px;">
                                <option value="">All Incomes</option>
                                @foreach($incomes as $income)
                                    <option value="{{ $income->id }}" @selected(($filters['income_id'] ?? '') == $income->id)>{{ $income->dropdown_label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
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
                    @if(isAdministrator() || (Auth::user()->role && in_array(Auth::user()->role->name, [\App\Models\User::DEVELOPER_ROLE_NAME, \App\Models\User::BUSINESS_DEVELOPER_ROLE_NAME])))
                        <a href="{{ route('voyager.dashboard', ['user_id' => auth()->id()]) }}" class="btn btn-default btn-sm" style="margin:4px;">
                            <i class="voyager-dashboard"></i> My Dashboard
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
                    @php
                        // Full field payload for the detail modal, shared by parent and child rows.
                        $detailPayload = function ($p) {
                            return [
                                'ID' => '#' . $p->id,
                                'User' => $p->developer->name ?? '-',
                                'Type' => ($p->share_type === 'business_developer' ? 'Business Developer commission' : 'Development Partner share')
                                    . ($p->earning_type === 'salary_employee' ? ' — salary employee earning' : ''),
                                'Project' => $p->project->name ?? '-',
                                'Project Target' => $p->projectTarget->title ?? '-',
                                'Income' => '#' . $p->income_id . ($p->income ? ' — ' . $p->income->amount . ' ' . strtoupper($p->income->amount_in ?? 'usd') . ' via ' . ucfirst($p->income->source ?? '-') : ''),
                                'Income Transaction Id' => $p->income->transaction_id ?? '-',
                                'Income Order Date' => $p->income ? (($p->income->transaction_date ?? $p->income->created_at?->format('Y-m-d')) ?? '-') : '-',
                                'Income Conversion Rate' => $p->income->conversion_rate ?? '-',
                                'Income Note' => $p->income->note ?? '-',
                                'Client Source' => ucfirst($p->client_source ?? '-'),
                                'Business Developer' => $p->businessDeveloper->name ?? '-',
                                'Total Earning $' => number_format($p->total_earning ?? 0, 2),
                                'Share $' => number_format($p->dev_earning ?? 0, 2),
                                'PKR Rate' => $p->currency_current_rate ?: '-',
                                'Payable PKR' => $p->payable ? number_format($p->payable, 2) : '-',
                                'Paid PKR' => $p->paid ? number_format($p->paid, 2) : '-',
                                'Status' => $p->paid ? 'Paid' : $p->status,
                                'Created' => $p->created_at?->format('d M Y H:i') ?? '-',
                                'Updated' => $p->updated_at?->format('d M Y H:i') ?? '-',
                                'System Generated' => $p->generated_by_system ? 'Yes' : 'No',
                                'Linked Request' => $p->second_entry_id ? '#' . $p->second_entry_id : '-',
                                'Notes' => $p->notes ?: '-',
                                // Rendered as an image slider below the field table - this
                                // request's own attachments (if the submitter added any).
                                '_attachments' => collect(json_decode($p->attachments ?? '[]', true) ?: [])
                                    ->map(fn($f) => Voyager::image(str_replace('\\', '/', $f)))
                                    ->values()->all(),
                                // The linked income's own attachments (the invoice/proof) -
                                // shown separately since this is what verification relies on.
                                '_income_attachments' => $p->income
                                    ? collect(json_decode($p->income->attachments ?? '[]', true) ?: [])
                                        ->map(fn($f) => Voyager::image(str_replace('\\', '/', $f)))
                                        ->values()->all()
                                    : [],
                                // Proof-of-payment invoice(s), attached separately from Mark
                                // Paid - administrator-only, same as the button that adds them.
                                '_paid_attachments' => isAdministrator()
                                    ? collect(json_decode($p->paid_attachments ?? '[]', true) ?: [])
                                        ->map(fn($f) => Voyager::image(str_replace('\\', '/', $f)))
                                        ->values()->all()
                                    : [],
                                // Who changed what, newest first (see UserPaymentObserver).
                                '_history' => $p->relationLoaded('logs')
                                    ? $p->logs->map(function ($log) {
                                        $fields = $log->changes ? implode(', ', array_keys($log->changes)) : null;
                                        return [
                                            'action' => $log->action,
                                            'actor' => $log->actor->name ?? 'System',
                                            'fields' => $fields,
                                            'at' => $log->created_at?->format('d M Y H:i'),
                                        ];
                                    })->values()->all()
                                    : [],
                            ];
                        };

                        // Quick-look payload for the income dialog (opened from the
                        // Income column) - avoids a full page redirect just to check
                        // an income's detail.
                        $incomeDetailPayload = function ($income) {
                            return [
                                'ID' => '#' . $income->id,
                                'Amount' => number_format($income->amount ?? 0, 2) . ' ' . strtoupper($income->amount_in ?? 'usd'),
                                'Source' => ucfirst($income->source ?? '-'),
                                'Transaction Id' => $income->transaction_id ?: '-',
                                'Order Date' => $income->transaction_date ?? $income->created_at?->format('Y-m-d') ?? '-',
                                'Conversion Rate' => $income->conversion_rate ?: '-',
                                'Note' => $income->note ?: '-',
                                'Created' => $income->created_at?->format('d M Y H:i') ?? '-',
                                '_url' => route('voyager.incomes.show', $income->id),
                                '_attachments' => collect(json_decode($income->attachments ?? '[]', true) ?: [])
                                    ->map(fn($f) => Voyager::image(str_replace('\\', '/', $f)))
                                    ->values()->all(),
                            ];
                        };
                    @endphp
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
                        <tr id="payment-{{ $payment->id }}" @if($child) class="pair-parent" @endif>
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
                                @if($payment->generated_by_system)
                                    <span class="label" style="background:#8492a6;" data-toggle="tooltip" title="Generated automatically by the system">Auto</span>
                                @elseif($payment->share_type === 'business_developer')
                                    <span class="label label-warning" data-toggle="tooltip" title="Added manually by the business development team">Manual</span>
                                @endif
                            </td>
                            <td>
                                {{ $payment->project->name ?? '-' }}
                                <br><small class="text-muted">{{ $payment->projectTarget->title ?? '' }}</small>
                            </td>
                            <td>
                                @if($payment->income)
                                    @php $incomeTitle = $payment->income->amount . ' ' . strtoupper($payment->income->amount_in ?? 'usd') . ' via ' . ucfirst($payment->income->source ?? '-') . ' on ' . ($payment->income->transaction_date ?? $payment->income->created_at?->format('Y-m-d') ?? '-') . ($payment->income->note ? ' - ' . \Illuminate\Support\Str::limit($payment->income->note, 120) : ''); @endphp
                                    @can('read', $payment->income)
                                        <a href="javascript:void(0)" class="view-income-btn" data-income="{{ json_encode($incomeDetailPayload($payment->income)) }}" title="{{ $incomeTitle }} (quick view)">
                                            #{{ $payment->income_id }} - ${{ number_format($payment->income->amount, 2) }}
                                            <br><small class="text-muted">{{ ucfirst($payment->income->source ?? '-') }}</small>
                                        </a>
                                    @else
                                        <span title="{{ $incomeTitle }}">
                                            #{{ $payment->income_id }} - ${{ number_format($payment->income->amount, 2) }}
                                            <br><small class="text-muted">{{ ucfirst($payment->income->source ?? '-') }}</small>
                                        </span>
                                    @endcan
                                @else
                                    #{{ $payment->income_id }}
                                @endif
                            </td>
                            <td>{{ ucfirst($payment->client_source ?? '-') }}</td>
                            <td class="text-right">{{ number_format($payment->total_earning ?? 0, 2) }}</td>
                            <td class="text-right">{{ number_format($payment->dev_earning ?? 0, 2) }}</td>
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
                                @if($canRateApprove && $payment->status === 'Requested')
                                    <button type="button" class="btn btn-success btn-xs rate-approve-btn"
                                            data-id="{{ $payment->id }}"
                                            data-name="{{ $payment->developer->name ?? '' }}"
                                            data-share="{{ $payment->dev_earning }}"
                                            data-rate="{{ $payment->income->conversion_rate ?? '' }}"
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
                                @if(isAdministrator() && !is_null($payment->paid))
                                    @php $hasInvoice = !empty(json_decode($payment->paid_attachments ?? '[]', true)); @endphp
                                    <button type="button" class="btn btn-xs attach-invoice-btn {{ $hasInvoice ? 'btn-success' : 'btn-default' }}"
                                            data-id="{{ $payment->id }}"
                                            title="{{ $hasInvoice ? 'Invoice already attached - click to add more' : 'Attach invoice' }}">
                                        <i class="voyager-paperclip"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-default btn-xs view-detail-btn"
                                        data-detail="{{ json_encode($detailPayload($payment)) }}" title="View details">
                                    <i class="voyager-eye"></i>
                                </button>
                                @if(isAdministrator())
                                    <a href="{{ route('voyager.user-payments.edit', $payment->id) }}" class="btn btn-primary btn-xs" title="{{ __('voyager::generic.edit') }}">
                                        <i class="voyager-edit"></i>
                                    </a>
                                @endif
                                @if(isAdministrator())
                                    <button type="button" class="btn btn-danger btn-xs delete-payment-btn"
                                            data-id="{{ $payment->id }}"
                                            data-linked="{{ ($child && $child->generated_by_system) ? $child->id : '' }}"
                                            title="Delete">
                                        <i class="voyager-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if($child)
                            <tr id="payment-{{ $child->id }}" class="pair-child">
                                <td style="padding-left:22px;">
                                    <i class="voyager-forward text-info"></i> {{ $child->id }}
                                    @if($pairWarning)
                                        <i class="voyager-warning text-warning" data-toggle="tooltip" title="{{ $pairWarning }}"></i>
                                    @endif
                                </td>
                                <td>{{ $child->developer->name ?? '-' }}</td>
                                <td>
                                    <span class="label label-info">BD</span>
                                    @if($child->generated_by_system)
                                        <span class="label" style="background:#8492a6;" data-toggle="tooltip" title="Generated automatically by the system">Auto</span>
                                    @else
                                        <span class="label label-warning" data-toggle="tooltip" title="Added manually by the business development team">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->project->name ?? '-' }}
                                    <br><small class="text-muted">{{ $payment->projectTarget->title ?? '' }}</small>
                                </td>
                                <td>
                                    @if($child->income)
                                        @php $childIncomeTitle = $child->income->amount . ' ' . strtoupper($child->income->amount_in ?? 'usd') . ' via ' . ucfirst($child->income->source ?? '-') . ' on ' . ($child->income->transaction_date ?? $child->income->created_at?->format('Y-m-d') ?? '-') . ($child->income->note ? ' - ' . \Illuminate\Support\Str::limit($child->income->note, 120) : ''); @endphp
                                        @can('read', $child->income)
                                            <a href="javascript:void(0)" class="view-income-btn" data-income="{{ json_encode($incomeDetailPayload($child->income)) }}" title="{{ $childIncomeTitle }} (quick view)">
                                                #{{ $child->income_id }} - ${{ number_format($child->income->amount, 2) }}
                                                <br><small class="text-muted">{{ ucfirst($child->income->source ?? '-') }}</small>
                                            </a>
                                        @else
                                            <span title="{{ $childIncomeTitle }}">
                                                #{{ $child->income_id }} - ${{ number_format($child->income->amount, 2) }}
                                                <br><small class="text-muted">{{ ucfirst($child->income->source ?? '-') }}</small>
                                            </span>
                                        @endcan
                                    @else
                                        #{{ $child->income_id }}
                                    @endif
                                </td>
                                <td>{{ ucfirst($child->client_source ?? '-') }}</td>
                                <td class="text-right text-muted">&mdash;</td>
                                <td class="text-right">{{ number_format($child->dev_earning ?? 0, 2) }}</td>
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
                                    @if($canRateApprove && $child->status === 'Requested')
                                        <button type="button" class="btn btn-success btn-xs rate-approve-btn"
                                                data-id="{{ $child->id }}"
                                                data-name="{{ $child->developer->name ?? '' }}"
                                                data-share="{{ $child->dev_earning }}"
                                                data-rate="{{ $child->income->conversion_rate ?? '' }}"
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
                                    @if(isAdministrator() && !is_null($child->paid))
                                        @php $childHasInvoice = !empty(json_decode($child->paid_attachments ?? '[]', true)); @endphp
                                        <button type="button" class="btn btn-xs attach-invoice-btn {{ $childHasInvoice ? 'btn-success' : 'btn-default' }}"
                                                data-id="{{ $child->id }}"
                                                title="{{ $childHasInvoice ? 'Invoice already attached - click to add more' : 'Attach invoice' }}">
                                            <i class="voyager-paperclip"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-default btn-xs view-detail-btn"
                                            data-detail="{{ json_encode($detailPayload($child)) }}" title="View details">
                                        <i class="voyager-eye"></i>
                                    </button>
                                    @if(isAdministrator())
                                        <a href="{{ route('voyager.user-payments.edit', $child->id) }}" class="btn btn-primary btn-xs" title="{{ __('voyager::generic.edit') }}">
                                            <i class="voyager-edit"></i>
                                        </a>
                                    @endif
                                    @if(isAdministrator())
                                        <button type="button" class="btn btn-danger btn-xs delete-payment-btn"
                                                data-id="{{ $child->id }}" data-linked=""
                                                title="Delete">
                                            <i class="voyager-trash"></i>
                                        </button>
                                    @endif
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

    {{-- Hidden delete form (Administrator only) --}}
    @if(isAdministrator())
        <form id="deletePaymentForm" method="POST" action="" style="display:none;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
    @endif

    {{-- Hidden invoice-upload form (Administrator only) - clicking the paperclip
         button opens the file picker directly; selecting file(s) submits and
         uploads immediately, no extra modal step. --}}
    @if(isAdministrator())
        <form id="attachInvoiceForm" method="POST" action="" enctype="multipart/form-data" style="display:none;">
            {{ csrf_field() }}
            <input type="file" name="paid_attachments[]" id="attachInvoiceInput" multiple accept="image/*,.pdf">
        </form>
    @endif

    {{-- Payment detail modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment Request <span id="detailTitle"></span></h4>
                </div>
                <div class="modal-body" style="max-height:75vh;overflow-y:auto;">
                    <table class="table table-condensed" id="detailTable" style="margin:0;">
                        <tbody></tbody>
                    </table>
                    <div id="detailAttachments" style="margin-top:12px;"></div>
                    <div id="detailHistory"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Income quick-view modal (Income column, view-income-btn) --}}
    <div class="modal fade" id="incomeDetailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Income <span id="incomeDetailTitle"></span></h4>
                </div>
                <div class="modal-body" style="max-height:75vh;overflow-y:auto;">
                    <table class="table table-condensed" id="incomeDetailTable" style="margin:0;">
                        <tbody></tbody>
                    </table>
                    <div id="incomeDetailAttachments" style="margin-top:12px;"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="incomeDetailFullPage" target="_blank" rel="noopener" class="btn btn-default pull-left">
                        <i class="voyager-eye"></i> Open full page
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
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
            // Searchable income filter (administrators only). Full width on
            // mobile so it matches the other stacked filter controls.
            if ($('#incomeFilter').length && $.fn.select2) {
                $('#incomeFilter').select2({
                    width: window.innerWidth < 768 ? '100%' : '260px',
                    allowClear: true,
                    placeholder: 'All Incomes'
                });
            }

            var rateApproveUrl = '{{ url('admin/user-payments') }}/__ID__/rate-approve';
            var currentShare = 0;

            $('.rate-approve-btn').on('click', function () {
                var id = $(this).data('id');
                currentShare = parseFloat($(this).data('share')) || 0;
                $('#rateApproveTitle').text('#' + id + ' - ' + $(this).data('name'));
                $('#rateApproveShare').text(currentShare.toFixed(2));
                $('#rateApproveForm').attr('action', rateApproveUrl.replace('__ID__', id));
                // Prefill with the income's conversion rate when recorded.
                var incomeRate = parseFloat($(this).data('rate'));
                $('#rateInput').val(!isNaN(incomeRate) && incomeRate > 0 ? incomeRate : '');
                $('#rateInput').trigger('input');
                $('#rateApproveModal').modal('show');
            });

            $('#rateInput').on('input', function () {
                var rate = parseFloat($(this).val());
                $('#rateApprovePayable').text(!isNaN(rate) ? (currentShare * rate).toFixed(2) : '-');
            });

            $('.delete-payment-btn').on('click', function () {
                var id = $(this).data('id');
                var linked = $(this).data('linked');
                var msg = 'Delete payment request #' + id + '?';
                if (linked) {
                    msg += '\nIts auto-generated BD commission #' + linked + ' will be deleted too.';
                }
                if (confirm(msg)) {
                    $('#deletePaymentForm')
                        .attr('action', '{{ url('admin/user-payments') }}/' + id)
                        .submit();
                }
            });

            // Attach invoice: click the paperclip -> file picker opens directly ->
            // picking file(s) uploads immediately, no extra confirmation step.
            $('.attach-invoice-btn').on('click', function () {
                $('#attachInvoiceForm').attr('action', '{{ url('admin/user-payments') }}/' + $(this).data('id') + '/attach-invoice');
                $('#attachInvoiceInput').trigger('click');
            });
            $('#attachInvoiceInput').on('change', function () {
                if (this.files.length) {
                    $('#attachInvoiceForm').submit();
                }
            });

            // Renders one attachment group (heading + inline image / slider / PDF
            // links) into a container. Used separately for the income's own
            // attachments (the invoice/proof) and the request's own attachments.
            function renderAttachmentGroup(container, attachments, heading) {
                if (!attachments.length) { return; }
                var images = attachments.filter(function (u) { return !/\.pdf(\?|$)/i.test(u); });
                var pdfs = attachments.filter(function (u) { return /\.pdf(\?|$)/i.test(u); });
                container.append('<h5 style="font-weight:600;color:#16202b;">' + heading + ' (' + attachments.length + ')</h5>');
                if (images.length === 1) {
                    container.append('<a href="' + images[0] + '" target="_blank"><img src="' + images[0] + '" style="max-width:100%;max-height:420px;display:block;margin:0 auto;border:1px solid #ddd;border-radius:4px;"></a>');
                } else if (images.length > 1) {
                    var indicators = '', inner = '';
                    images.forEach(function (u, i) {
                        indicators += '<li data-dc-to="' + i + '"' + (i === 0 ? ' class="active"' : '') + '></li>';
                        inner += '<div class="dc-item' + (i === 0 ? ' active' : '') + '"><a href="' + u + '" target="_blank"><img src="' + u + '"></a></div>';
                    });
                    container.append(
                        '<div class="dc-carousel">' +
                            inner +
                            '<a href="javascript:void(0)" class="dc-control dc-prev" data-dc="prev">&lsaquo;</a>' +
                            '<a href="javascript:void(0)" class="dc-control dc-next" data-dc="next">&rsaquo;</a>' +
                            '<ol class="dc-indicators">' + indicators + '</ol>' +
                        '</div>' +
                        '<p class="text-center" style="margin-top:5px;color:#4a5561;"><small>' + images.length + ' images - click an image to open full size</small></p>'
                    );
                }
                pdfs.forEach(function (u) {
                    container.append('<a href="' + u + '" target="_blank" class="btn btn-xs btn-default" style="margin:5px 5px 0 0;"><i class="voyager-file-text"></i> ' + u.split('/').pop() + '</a>');
                });
            }

            $('.view-detail-btn').on('click', function () {
                var detail = $(this).data('detail') || {};
                var requestAttachments = detail['_attachments'] || [];
                var incomeAttachments = detail['_income_attachments'] || [];
                var paidAttachments = detail['_paid_attachments'] || [];
                var history = detail['_history'] || [];
                $('#detailTitle').text(detail['ID'] || '');
                var tbody = $('#detailTable tbody').empty();
                $.each(detail, function (label, value) {
                    if (label.indexOf('_') === 0) { return; } // skip _attachments, _income_attachments, _paid_attachments, _history
                    var row = $('<tr>');
                    row.append($('<th>').css({whiteSpace: 'nowrap', width: '170px'}).text(label));
                    var cell = $('<td>').text(value === null || value === '' ? '-' : String(value));
                    if (label === 'Notes' || label === 'Income Note') { cell.css('white-space', 'pre-line'); }
                    row.append(cell);
                    tbody.append(row);
                });

                var wrap = $('#detailAttachments').empty();
                renderAttachmentGroup(wrap, paidAttachments, 'Invoice (proof of payment)');
                renderAttachmentGroup(wrap, incomeAttachments, 'Income Attachments (proof)');
                renderAttachmentGroup(wrap, requestAttachments, 'Request Attachments');

                // Activity log: who changed what, newest first.
                var historyWrap = $('#detailHistory').empty();
                if (history.length) {
                    historyWrap.append('<h5 style="font-weight:600;color:#16202b;margin-top:14px;">Activity Log</h5>');
                    var list = $('<ul>').css({listStyle: 'none', padding: 0, margin: 0, fontSize: '12.5px'});
                    history.forEach(function (h) {
                        var line = '<strong>' + h.actor + '</strong> ' + h.action + (h.fields ? ' (' + h.fields + ')' : '') + ' &middot; <span style="color:#4a5561;">' + (h.at || '-') + '</span>';
                        list.append($('<li>').css({padding: '3px 0', borderBottom: '1px solid #eee'}).html(line));
                    });
                    historyWrap.append(list);
                }

                $('#detailModal').modal('show');
            });

            // Income quick-view dialog (Income column) - shows the income's own
            // fields + attachments without a page redirect. "Open full page"
            // still links to the real income record if a fuller view is needed.
            $('.view-income-btn').on('click', function () {
                var income = $(this).data('income') || {};
                $('#incomeDetailTitle').text(income['ID'] || '');
                var tbody = $('#incomeDetailTable tbody').empty();
                $.each(income, function (label, value) {
                    if (label.indexOf('_') === 0) { return; } // skip _url, _attachments
                    var row = $('<tr>');
                    row.append($('<th>').css({whiteSpace: 'nowrap', width: '150px'}).text(label));
                    var cell = $('<td>').text(value === null || value === '' ? '-' : String(value));
                    if (label === 'Note') { cell.css('white-space', 'pre-line'); }
                    row.append(cell);
                    tbody.append(row);
                });

                var wrap = $('#incomeDetailAttachments').empty();
                renderAttachmentGroup(wrap, income['_attachments'] || [], 'Attachments');

                $('#incomeDetailFullPage').attr('href', income['_url'] || '#');
                $('#incomeDetailModal').modal('show');
            });

            // Attachment slider navigation (self-contained, no plugin needed) -
            // scoped to whichever .dc-carousel the click happened inside, so
            // multiple sliders (request/income/income-dialog) can coexist
            // independently.
            $('#detailAttachments, #incomeDetailAttachments').on('click', '[data-dc], [data-dc-to]', function () {
                var $c = $(this).closest('.dc-carousel');
                var $items = $c.find('.dc-item');
                var $dots = $c.find('.dc-indicators li');
                var current = $items.index($items.filter('.active'));
                var to;
                if ($(this).data('dc') === 'prev') {
                    to = (current - 1 + $items.length) % $items.length;
                } else if ($(this).data('dc') === 'next') {
                    to = (current + 1) % $items.length;
                } else {
                    to = parseInt($(this).attr('data-dc-to'), 10) || 0;
                }
                $items.removeClass('active').eq(to).addClass('active');
                $dots.removeClass('active').eq(to).addClass('active');
            });
        });
    </script>
@stop
