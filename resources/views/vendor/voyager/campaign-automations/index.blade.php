@extends('voyager::master')

@section('page_title', 'Campaign Automations')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i> Campaign Automations
    </h1>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
                    <h3 class="panel-title">Scheduled Automations</h3>
                    <a href="{{ route('campaign-automations.create') }}" class="btn btn-success btn-sm">
                        <i class="voyager-plus"></i> New Automation
                    </a>
                </div>
                <div class="panel-body" style="padding:0;">
                    <table class="table table-hover" style="margin:0;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Campaign</th>
                                <th>Frequency</th>
                                <th>Batch / Gap</th>
                                <th>Status</th>
                                <th>Next Run</th>
                                <th>Total Sent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($automations as $auto)
                            <tr>
                                <td><strong>{{ $auto->name }}</strong></td>
                                <td>{{ $auto->campaign->name ?? '—' }}</td>
                                <td>{{ $auto->frequency_label }}</td>
                                <td>
                                    {{ $auto->batch_size }}/run
                                    @if($auto->email_delay_seconds > 0)
                                        @php $d = $auto->email_delay_seconds; @endphp
                                        <span class="label label-info" style="font-size:10px;">
                                            {{ $d >= 60 ? floor($d/60).'m'.($d%60?$d%60 .'s':'') : $d.'s' }} delay
                                        </span>
                                    @endif
                                    <br>
                                    <span class="text-muted" style="font-size:11px;">
                                        gap: {{ $auto->resend_gap_days === 0 ? 'never resend' : $auto->resend_gap_days . 'd' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $auto->status_badge }}">{{ ucfirst($auto->status) }}</span>
                                </td>
                                <td style="font-size:12px;">
                                    {{ $auto->next_run_at ? $auto->next_run_at->format('d M Y H:i') : '—' }}
                                </td>
                                <td>{{ number_format($auto->emails_sent_total) }}</td>
                                <td>
                                    <div style="display:flex;gap:3px;align-items:center;flex-wrap:wrap;">
                                        <a href="{{ route('campaign-automations.show', $auto->id) }}" class="btn btn-default btn-sm" title="Logs">
                                            <i class="voyager-list"></i>
                                        </a>
                                        <a href="{{ route('campaign-automations.edit', $auto->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="voyager-edit"></i>
                                        </a>

                                        @if(in_array($auto->status, ['active','paused']))
                                            <form method="POST" action="{{ route('campaign-automations.run-now', $auto->id) }}" style="display:contents;">
                                                @csrf
                                                <button class="btn btn-info btn-sm" title="Run Now — send next batch immediately"
                                                        onclick="return confirm('Send the next batch for \"{{ addslashes($auto->name) }}\" right now?')">
                                                    <i class="voyager-forward"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($auto->status === 'active')
                                            <form method="POST" action="{{ route('campaign-automations.pause', $auto->id) }}" style="display:contents;">
                                                @csrf
                                                <button class="btn btn-warning btn-sm" title="Pause" onclick="return confirm('Pause this automation?')">
                                                    <i class="fa fa-pause"></i>
                                                </button>
                                            </form>
                                        @elseif($auto->status === 'paused')
                                            <form method="POST" action="{{ route('campaign-automations.resume', $auto->id) }}" style="display:contents;">
                                                @csrf
                                                <button class="btn btn-success btn-sm" title="Resume">
                                                    <i class="voyager-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($auto->status, ['active','paused']))
                                            <form method="POST" action="{{ route('campaign-automations.cancel', $auto->id) }}" style="display:contents;">
                                                @csrf
                                                <button class="btn btn-danger btn-sm" title="Cancel" onclick="return confirm('Cancel this automation?')">
                                                    <i class="voyager-x"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('campaign-automations.destroy', $auto->id) }}" style="display:contents;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Delete this automation and all its logs?')">
                                                <i class="voyager-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding:40px;">
                                    No automations yet. <a href="{{ route('campaign-automations.create') }}">Create one</a>.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($automations->hasPages())
                    <div class="panel-footer">{{ $automations->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
