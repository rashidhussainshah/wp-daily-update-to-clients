@extends('voyager::bread.read')

{{--
    Adds a quick "log a repair" box (battery, hard drive, etc.) below the
    standard BREAD read layout. @parent pulls in Voyager's default content
    section untouched; nothing else about the stock read page is changed.
--}}
@section('content')
    @parent

    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="voyager-wrench"></i> Maintenance / Repair Log</h3>
            </div>
            <div class="panel-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @can('edit', $dataTypeContent)
                <form method="POST" action="{{ route('company-device-maintenance-logs.store', $dataTypeContent->id) }}" style="margin-bottom:25px;">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Quick Type</label>
                                <select name="type" class="form-control">
                                    <option value="">— Select —</option>
                                    @foreach(\App\Models\CompanyDeviceMaintenanceLog::TYPES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Details</label>
                                <textarea name="note" class="form-control" rows="2" required
                                          placeholder="e.g. Replaced battery, old one was swelling"></textarea>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="voyager-plus"></i> Log It
                            </button>
                        </div>
                    </div>
                </form>
                @endcan

                <table class="table table-hover" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="width:110px;">Date</th>
                            <th style="width:180px;">Type</th>
                            <th>Details</th>
                            <th style="width:140px;">Logged By</th>
                            @can('edit', $dataTypeContent)
                                <th style="width:60px;"></th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($dataTypeContent->maintenanceLogs as $log)
                        <tr>
                            <td>{{ optional($log->logged_at)->format('d M Y') }}</td>
                            <td>{{ \App\Models\CompanyDeviceMaintenanceLog::TYPES[$log->type] ?? ($log->type ?: '—') }}</td>
                            <td>{{ $log->note }}</td>
                            <td>{{ optional($log->loggedBy)->name ?? '—' }}</td>
                            @can('edit', $dataTypeContent)
                                <td>
                                    <form method="POST" action="{{ route('company-device-maintenance-logs.destroy', $log->id) }}"
                                          onsubmit="return confirm('Remove this maintenance entry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Remove">
                                            <i class="voyager-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:30px;">
                                No maintenance logged yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@stop
