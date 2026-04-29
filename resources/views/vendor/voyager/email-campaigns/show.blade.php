@extends('voyager::master')

@section('page_title', $campaign->name)

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i> {{ $campaign->name }}
        @php $colors = ['draft'=>'default','sending'=>'warning','sent'=>'success','paused'=>'danger','scheduled'=>'info']; @endphp
        <span class="label label-{{ $colors[$campaign->status] ?? 'default' }}" style="font-size:14px;vertical-align:middle;">
            {{ ucfirst($campaign->status) }}
        </span>
    </h1>
    <a href="{{ route('email-campaigns.index') }}" class="btn btn-default">
        <i class="voyager-angle-left"></i> All Campaigns
    </a>
    @if(!$campaign->isSent())
    <a href="{{ route('email-campaigns.edit', $campaign->id) }}" class="btn btn-warning">
        <i class="voyager-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('email-campaigns.preview', $campaign->id) }}" target="_blank" class="btn btn-info">
        <i class="voyager-browser"></i> Preview Email
    </a>
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

        {{-- Stats cards ──────────────────────────────────────────────────── --}}
        <div class="col-md-3">
            <div class="tile tile-primary" style="background:#1a1a2e;color:#fff;padding:20px;border-radius:8px;text-align:center;">
                <div style="font-size:36px;font-weight:700;">{{ number_format($stats['total']) }}</div>
                <div>Total Recipients</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tile tile-success" style="background:#27ae60;color:#fff;padding:20px;border-radius:8px;text-align:center;">
                <div style="font-size:36px;font-weight:700;">{{ number_format($stats['sent']) }}</div>
                <div>Sent</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tile tile-warning" style="background:#f39c12;color:#fff;padding:20px;border-radius:8px;text-align:center;">
                <div style="font-size:36px;font-weight:700;">{{ number_format($stats['pending']) }}</div>
                <div>Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tile tile-danger" style="background:#e74c3c;color:#fff;padding:20px;border-radius:8px;text-align:center;">
                <div style="font-size:36px;font-weight:700;">{{ number_format($stats['failed']) }}</div>
                <div>Failed</div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">

        {{-- Left: campaign info + actions ──────────────────────────────── --}}
        <div class="col-md-4">

            {{-- Campaign Details --}}
            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">Campaign Details</h3></div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <tr><td><strong>Subject</strong></td><td>{{ $campaign->subject }}</td></tr>
                        <tr><td><strong>From</strong></td><td>{{ $campaign->from_name }} &lt;{{ $campaign->from_email }}&gt;</td></tr>
                        <tr><td><strong>Target Role</strong></td><td><span class="label label-default">{{ $campaign->target_role }}</span></td></tr>
                        <tr><td><strong>Created</strong></td><td>{{ $campaign->created_at->format('d M Y H:i') }}</td></tr>
                        @if($campaign->sent_at)
                        <tr><td><strong>Sent At</strong></td><td>{{ $campaign->sent_at->format('d M Y H:i') }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Test Send --}}
            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">🧪 Send Test Email</h3></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('email-campaigns.send-test', $campaign->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Test Email Address</label>
                            <input type="email" name="test_email" class="form-control"
                                   value="{{ auth()->user()->email }}" placeholder="you@example.com">
                        </div>
                        <div class="form-group">
                            <label>Test Name</label>
                            <input type="text" name="test_name" class="form-control" value="John" placeholder="John">
                        </div>
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="voyager-mail"></i> Send Test
                        </button>
                    </form>
                </div>
            </div>

            {{-- Send to Single Email --}}
            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">✉️ Send to One Email</h3></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('email-campaigns.send-single', $campaign->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="to_email" class="form-control" placeholder="recipient@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Recipient Name</label>
                            <input type="text" name="to_name" class="form-control" placeholder="Full Name">
                        </div>
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="voyager-mail"></i> Send Now
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bulk Dispatch --}}
            @if(!$campaign->isSent())
            <div class="panel panel-bordered" style="border-color:#27ae60;">
                <div class="panel-heading" style="background:#27ae60;color:#fff;">
                    <h3 class="panel-title">🚀 Bulk Send to All {{ number_format($stats['total']) }} Users</h3>
                </div>
                <div class="panel-body">
                    <p class="text-muted" style="font-size:13px;">
                        This will queue emails for all <strong>{{ $campaign->target_role }}</strong> users.
                        Already-sent addresses are automatically skipped.
                    </p>
                    <form method="POST" action="{{ route('email-campaigns.dispatch', $campaign->id) }}"
                          onsubmit="return confirm('Queue bulk send to {{ number_format($stats['total']) }} recipients?')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block btn-lg">
                            <i class="voyager-send"></i> Queue Bulk Send
                        </button>
                    </form>
                    @if($campaign->status === 'sending')
                    <form method="POST" action="{{ route('email-campaigns.mark-complete', $campaign->id) }}" style="margin-top:8px;">
                        @csrf
                        <button type="submit" class="btn btn-default btn-block btn-sm">Mark as Complete</button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Right: send logs ──────────────────────────────────────────── --}}
        <div class="col-md-8">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Send Log (latest {{ $logs->perPage() }})</h3>
                </div>
                <div class="panel-body" style="padding:0;">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->email }}</td>
                                <td>{{ $log->name }}</td>
                                <td>
                                    @if($log->status === 'sent')
                                        <span class="label label-success">Sent</span>
                                    @elseif($log->status === 'failed')
                                        <span class="label label-danger">Failed</span>
                                    @else
                                        <span class="label label-default">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $log->sent_at?->format('d M H:i') ?? '—' }}</td>
                                <td style="max-width:200px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                                    {{ Str::limit($log->error, 60) }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted" style="padding:30px;">No emails sent yet.</td></tr>
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
