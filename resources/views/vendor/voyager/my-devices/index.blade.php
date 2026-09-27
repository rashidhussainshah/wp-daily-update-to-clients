@extends('voyager::master')

@section('page_title', 'My Devices')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-laptop"></i> My Devices</h1>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Currently Assigned To Me</h3>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="table table-hover" style="margin:0;">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Type</th>
                        <th>Brand / Model</th>
                        <th>Serial</th>
                        <th>Asset Tag</th>
                        <th>Assigned Since</th>
                        <th>Recent Maintenance</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($devices as $device)
                    <tr>
                        <td><strong>{{ $device->name }}</strong></td>
                        <td>{{ \App\Models\CompanyDevice::TYPES[$device->type] ?? ucfirst($device->type) }}</td>
                        <td>{{ trim(($device->brand ?? '') . ' ' . ($device->model ?? '')) ?: '—' }}</td>
                        <td>{{ $device->serial_number ?: '—' }}</td>
                        <td>{{ $device->asset_tag ?: '—' }}</td>
                        <td>{{ optional($device->assigned_at)->format('d M Y') ?: '—' }}</td>
                        <td>
                            @forelse($device->maintenanceLogs->take(2) as $log)
                                <div>
                                    <small class="text-muted">{{ optional($log->logged_at)->format('d M Y') }}</small>
                                    — {{ \App\Models\CompanyDeviceMaintenanceLog::TYPES[$log->type] ?? $log->note }}
                                </div>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding:40px;">
                            No company devices are currently assigned to you.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">My Handover History</h3>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="table table-hover" style="margin:0;">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Assigned On</th>
                        <th>Returned On</th>
                        <th>Condition Out / In</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($history as $row)
                    <tr>
                        <td>{{ optional($row->device)->name ?? '—' }}</td>
                        <td>{{ optional($row->assigned_on)->format('d M Y') ?: '—' }}</td>
                        <td>
                            @if($row->returned_on)
                                {{ $row->returned_on->format('d M Y') }}
                            @else
                                <span class="badge badge-success">Still with me</span>
                            @endif
                        </td>
                        <td>{{ strtoupper($row->condition_on_assign ?: '—') }} / {{ strtoupper($row->condition_on_return ?: '—') }}</td>
                        <td>{{ optional($row->assigner)->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding:40px;">
                            No handover history yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop
