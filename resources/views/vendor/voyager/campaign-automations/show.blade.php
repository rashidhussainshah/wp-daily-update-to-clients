@extends('voyager::master')

@section('page_title', 'Automation Logs — ' . $automation->name)

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title" style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;">
        <span><i class="voyager-list"></i> {{ $automation->name }}</span>
        <span class="badge badge-{{ $automation->status_badge }}">{{ ucfirst($automation->status) }}</span>
        <span class="label label-{{ $automation->runtime_status['badge'] }}" style="font-size:12px;">
            {{ $automation->runtime_status['text'] }}
        </span>
    </h1>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

    <div class="row">
        {{-- Summary card --}}
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="row text-center">
                        <div class="col-xs-3">
                            <h3 style="margin:0;color:#5cb85c;">{{ number_format($stats['sent']) }}</h3>
                            <small class="text-muted">Total Sent</small>
                        </div>
                        <div class="col-xs-3">
                            <h3 style="margin:0;color:#d9534f;">{{ number_format($stats['failed']) }}</h3>
                            <small class="text-muted">Failed</small>
                        </div>
                        <div class="col-xs-3">
                            <h3 style="margin:0;">{{ $automation->frequency_label }}</h3>
                            <small class="text-muted">
                                Next: {{ $automation->next_run_at ? $automation->next_run_at->format('d M Y H:i:s') : '—' }}
                            </small>
                        </div>
                        <div class="col-xs-3">
                            @if($automation->email_delay_seconds > 0)
                                @php $d = $automation->email_delay_seconds; @endphp
                                <h3 style="margin:0;">
                                    {{ $d >= 60 ? floor($d/60).'m' .($d%60 ? ' '.$d%60 .'s' : '') : $d.'s' }}
                                </h3>
                                <small class="text-muted">Email Delay</small>
                                @if($automation->emails_sent_in_batch > 0)
                                    <br>
                                    <span class="label label-warning">
                                        Batch in progress: {{ $automation->emails_sent_in_batch }}/{{ $automation->batch_size }}
                                    </span>
                                @endif
                            @else
                                <h3 style="margin:0;">{{ $automation->batch_size }}</h3>
                                <small class="text-muted">Batch Size</small>
                            @endif
                        </div>
                    </div>
                    <hr style="margin:16px 0 8px;">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="{{ route('campaign-automations.index') }}" class="btn btn-default btn-sm">
                            <i class="voyager-angle-left"></i> Back
                        </a>
                        <a href="{{ route('campaign-automations.edit', $automation->id) }}" class="btn btn-primary btn-sm">
                            <i class="voyager-edit"></i> Edit
                        </a>

                        @if(in_array($automation->status, ['active','paused']))
                            <form method="POST" action="{{ route('campaign-automations.run-now', $automation->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-info btn-sm"
                                        onclick="return confirm('Send the next batch right now?')">
                                    <i class="voyager-forward"></i> Run Now
                                </button>
                            </form>
                        @endif

                        @if($automation->status === 'active')
                            <form method="POST" action="{{ route('campaign-automations.pause', $automation->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-warning btn-sm" onclick="return confirm('Pause this automation?')">
                                    <i class="voyager-spinner"></i> Pause
                                </button>
                            </form>
                        @elseif($automation->status === 'paused')
                            <form method="POST" action="{{ route('campaign-automations.resume', $automation->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    <i class="voyager-check"></i> Resume
                                </button>
                            </form>
                        @endif

                        @if(in_array($automation->status, ['active','paused']))
                            <form method="POST" action="{{ route('campaign-automations.cancel', $automation->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Cancel this automation?')">
                                    <i class="voyager-x"></i> Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Log table --}}
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Send Log</h3>
                </div>
                <div class="panel-body" style="padding:0;overflow-x:auto;">
                    <table class="table table-hover" style="margin:0;min-width:700px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Error</th>
                                <th>Sent At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-muted" style="font-size:12px;">{{ $log->id }}</td>
                                <td>{{ $log->email }}</td>
                                <td>{{ $log->name ?: '—' }}</td>
                                <td>
                                    <span class="badge badge-{{ $log->status === 'sent' ? 'success' : 'danger' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:#c00;max-width:280px;word-break:break-word;">
                                    {{ $log->error ?? '' }}
                                </td>
                                <td style="font-size:12px;">
                                    {{ $log->sent_at ? $log->sent_at->format('d M Y H:i:s') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:32px;">
                                    No emails sent yet for this automation.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="panel-footer">{{ $logs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
