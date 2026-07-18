@extends('voyager::master')

@section('page_title', 'Team Statistics')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-people"></i> Team Statistics
        </h1>
        <a href="{{ route('user-payments.statistics') }}" class="btn btn-default">
            <i class="voyager-bar-chart"></i> <span>Payment Statistics</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')

        {{-- Filters --}}
        <div class="panel panel-bordered">
            <div class="panel-body">
                @include('voyager::user-payments._date_presets')
                <form method="GET" action="{{ route('team-statistics.index') }}" class="form-inline">
                    <div class="form-group" style="margin:4px;">
                        <select name="user_id" class="form-control input-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <input type="date" name="date_from" class="form-control input-sm" value="{{ $filters['date_from'] ?? '' }}" title="From date">
                    </div>
                    <div class="form-group" style="margin:4px;">
                        <input type="date" name="date_to" class="form-control input-sm" value="{{ $filters['date_to'] ?? '' }}" title="To date">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin:4px;"><i class="voyager-search"></i> Apply</button>
                    <a href="{{ route('team-statistics.index') }}" class="btn btn-default btn-sm" style="margin:4px;">Reset</a>
                </form>
            </div>
        </div>

        {{-- Leaves cards --}}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #2a78d6;">
                    <small class="text-muted">Leave Requests / Days</small>
                    <h4 style="margin:4px 0;">{{ $leaveStats->requests }} <small>/ {{ $leaveStats->days }} days</small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #2a78d6;">
                    <small class="text-muted">Leaves Approved / Pending</small>
                    <h4 style="margin:4px 0;">{{ $leaveStats->approved ?? 0 }} <small>/ {{ $leaveStats->pending ?? 0 }} pending</small></h4>
                    <small class="text-muted">{{ $leaveStats->coo_required ?? 0 }} needed COO approval</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #eda100;">
                    <small class="text-muted">Fines Total / Deducted</small>
                    <h4 style="margin:4px 0;">{{ number_format($fineStats->amount, 0) }} <small>/ {{ number_format($fineStats->deducted_amount, 0) }} deducted</small></h4>
                    <small class="text-muted">{{ $fineStats->fines }} fines &middot; {{ number_format($fineStats->pending_amount, 0) }} pending &middot; {{ number_format($fineStats->paid, 0) }} paid</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #1baf7a;">
                    <small class="text-muted">Checkins / Avg Hours</small>
                    <h4 style="margin:4px 0;">{{ $checkinStats->checkins }} <small>/ {{ $checkinStats->avg_hours ?? '-' }} h avg</small></h4>
                    <small class="text-muted">{{ $checkinStats->users }} users &middot; avg in {{ $checkinStats->avg_in ? substr($checkinStats->avg_in, 0, 5) : '-' }} &middot; out {{ $checkinStats->avg_out ? substr($checkinStats->avg_out, 0, 5) : '-' }} &middot; {{ $checkinStats->missing_checkout ?? 0 }} missing checkout</small>
                </div>
            </div>
        </div>

        {{-- Policy compliance cards --}}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #0ca30c;">
                    <small class="text-muted">Leave Rule (max 2 days/month)</small>
                    <h4 style="margin:4px 0;"><span class="text-success">{{ $followingCount }} following</span> <small>/ <span class="{{ $exceedingCount > 0 ? 'text-danger' : '' }}">{{ $exceedingCount }} exceeding</span></small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #2a78d6;">
                    <small class="text-muted">Avg Leave Days / User / Month</small>
                    <h4 style="margin:4px 0;">{{ $avgLeavePerUserMonth }} <small>/ 2 allowed</small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid #1baf7a;">
                    <small class="text-muted">Avg Shift / Net Working Hours</small>
                    <h4 style="margin:4px 0;">{{ $checkinStats->avg_hours ?? '-' }}h <small>/ {{ $checkinStats->avg_net_hours ?? '-' }}h net (target 9h incl. 1h break)</small></h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                @php
                    $completedDays = ($checkinStats->full_days ?? 0) + ($checkinStats->short_days ?? 0);
                    $fullPct = $completedDays ? round($checkinStats->full_days / $completedDays * 100) : 0;
                @endphp
                <div class="panel panel-bordered" style="padding:12px 16px;border-left:4px solid {{ $fullPct >= 80 ? '#0ca30c' : '#d03b3b' }};">
                    <small class="text-muted">Full Days (&ge;9h) vs Short Days</small>
                    <h4 style="margin:4px 0;">{{ $checkinStats->full_days ?? 0 }} <small>/ {{ $checkinStats->short_days ?? 0 }} short ({{ $fullPct }}% full)</small></h4>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Leave Days per Month</h3></div>
                    <div class="panel-body"><canvas id="leavesChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Fines Amount per Month</h3></div>
                    <div class="panel-body"><canvas id="finesChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Checkins per Month</h3></div>
                    <div class="panel-body"><canvas id="checkinsChart" height="220"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Per-user tables --}}
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Leaves by User</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>User</th><th class="text-right">Days</th><th class="text-right">Avg/Mo</th><th class="text-right">Max Mo</th><th class="text-right">Months &gt;2</th><th>Rule</th></tr></thead>
                            <tbody>
                            @forelse($leavesByUser as $row)
                                @php $comp = $leaveCompliance[$row->user_id] ?? null; @endphp
                                <tr>
                                    <td>{{ $userNames[$row->user_id] ?? ('#' . $row->user_id) }}
                                        <br><small class="text-muted">{{ $row->requests }} req &middot; {{ $row->approved }} approved &middot; {{ $row->pending }} pending</small>
                                    </td>
                                    <td class="text-right">{{ $row->days }}</td>
                                    <td class="text-right">{{ $comp->avg_per_month ?? '-' }}</td>
                                    <td class="text-right">{{ $comp->max_month_days ?? '-' }}</td>
                                    <td class="text-right {{ ($comp->over_months ?? 0) > 0 ? 'text-danger' : '' }}">{{ $comp->over_months ?? 0 }}</td>
                                    <td>
                                        @if($comp && $comp->following)
                                            <span class="label label-success">Following</span>
                                        @elseif($comp)
                                            <span class="label label-danger">Exceeds</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No leaves in range.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Fines by User</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>User</th><th class="text-right">Fines</th><th class="text-right">Amount</th><th class="text-right">Deducted</th><th class="text-right">Paid</th></tr></thead>
                            <tbody>
                            @forelse($finesByUser as $row)
                                <tr>
                                    <td>{{ $userNames[$row->user_id] ?? ('#' . $row->user_id) }}</td>
                                    <td class="text-right">{{ $row->fines }}</td>
                                    <td class="text-right">{{ number_format($row->amount, 0) }}</td>
                                    <td class="text-right">{{ number_format($row->deducted_amount, 0) }}</td>
                                    <td class="text-right">{{ number_format($row->paid, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No fines in range.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Attendance by User</h3></div>
                    <div class="panel-body table-responsive">
                        <table class="table table-condensed">
                            <thead><tr><th>User</th><th class="text-right">Days</th><th class="text-right">Avg In/Out</th><th class="text-right">Avg Hrs</th><th class="text-right">Net Hrs</th><th class="text-right">Full/Short</th><th class="text-right">No Out</th></tr></thead>
                            <tbody>
                            @forelse($checkinsByUser as $row)
                                <tr>
                                    <td>{{ $userNames[$row->developer_id] ?? ('#' . $row->developer_id) }}</td>
                                    <td class="text-right">{{ $row->days }}</td>
                                    <td class="text-right"><small>{{ $row->avg_in ? substr($row->avg_in, 0, 5) : '-' }} / {{ $row->avg_out ? substr($row->avg_out, 0, 5) : '-' }}</small></td>
                                    <td class="text-right {{ ($row->avg_hours ?? 0) < 9 ? 'text-danger' : 'text-success' }}">{{ $row->avg_hours ?? '-' }}</td>
                                    <td class="text-right">{{ $row->avg_net_hours ?? '-' }}</td>
                                    <td class="text-right">{{ $row->full_days ?? 0 }} / <span class="{{ ($row->short_days ?? 0) > ($row->full_days ?? 0) ? 'text-danger' : '' }}">{{ $row->short_days ?? 0 }}</span></td>
                                    <td class="text-right {{ $row->missing_checkout > 0 ? 'text-danger' : '' }}">{{ $row->missing_checkout }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No checkins in range.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Fixed module colors: leaves = blue, checkins = aqua, fines = yellow.
        var C = { leaves: '#2a78d6', checkins: '#1baf7a', fines: '#eda100' };
        var ink = '#52514e', grid = '#e1e0d9';
        var monthly = {!! json_encode($monthly) !!};

        function barChart(id, data, color, label) {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: monthly.labels,
                    datasets: [{ label: label, data: data, backgroundColor: color, borderRadius: 4, categoryPercentage: 0.6, barPercentage: 0.9 }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: grid }, ticks: { color: ink } },
                        x: { grid: { display: false }, ticks: { color: ink } }
                    }
                }
            });
        }

        barChart('leavesChart', monthly.leaves, C.leaves, 'Leave days');
        barChart('finesChart', monthly.fines, C.fines, 'Fines amount');
        barChart('checkinsChart', monthly.checkins, C.checkins, 'Checkins');
    </script>
@stop
