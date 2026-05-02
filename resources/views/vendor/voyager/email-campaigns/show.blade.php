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

            {{-- Send to Selected Clients ──────────────────────────────── --}}
            <div class="panel panel-bordered" style="border-color:#1a1a2e;">
                <div class="panel-heading" style="background:#1a1a2e;color:#fff;">
                    <h3 class="panel-title" style="color:#fff;font-size:15px;font-weight:700;">👥 Send to Selected Clients</h3>
                </div>
                <div class="panel-body">
                    <p style="font-size:14px;color:#555;margin-bottom:14px;line-height:1.5;">
                        Search and pick individual clients. Clients already sent <em>this</em> campaign won't appear.
                    </p>
                    <form method="POST" action="{{ route('email-campaigns.send-to-selected', $campaign->id) }}"
                          id="send-selected-form">
                        @csrf
                        <div class="form-group" style="margin-bottom:14px;">
                            <select name="user_ids[]" id="recipient-select" class="form-control"
                                    multiple="multiple" style="width:100%;"
                                    data-placeholder="Type name or email to search…">
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="send-selected-btn" disabled>
                            <i class="voyager-send"></i>
                            <span id="send-selected-label">Select recipients first</span>
                        </button>
                    </form>
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
@endsection

@section('javascript')
<style>
/* ── Dropdown result rows ─────────────────────────────────────────────── */
.select2-results__option {
    padding: 10px 14px !important;
    font-size: 14px !important;
    line-height: 1.4 !important;
    color: #222 !important;
}
.select2-results__option.select2-results__option--highlighted {
    background: #1a1a2e !important;
    color: #fff !important;
}
/* ── Selected tags (chips) ────────────────────────────────────────────── */
#select2-recipient-select-container ~ .select2-selection__choice,
.select2-selection--multiple .select2-selection__choice {
    background: #1a1a2e !important;
    border-color: #1a1a2e !important;
    color: #fff !important;
    font-size: 13px !important;
    padding: 4px 10px !important;
    border-radius: 4px !important;
    line-height: 1.6 !important;
    max-width: 100% !important;
}
.select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,0.75) !important;
    margin-right: 6px !important;
    font-size: 16px !important;
    font-weight: 700 !important;
}
.select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fff !important;
}
/* ── Search input ─────────────────────────────────────────────────────── */
.select2-search--inline .select2-search__field {
    font-size: 14px !important;
    margin-top: 6px !important;
}
/* ── The multi-select box itself ──────────────────────────────────────── */
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    min-height: 44px !important;
    padding: 4px 6px !important;
}
</style>
<script>
$(function () {
    var recipientsUrl = '{{ route('email-campaigns.recipients', $campaign->id) }}';

    $('#recipient-select').select2({
        width: '100%',
        placeholder: 'Type name or email to search…',
        minimumInputLength: 1,
        ajax: {
            url: recipientsUrl,
            dataType: 'json',
            delay: 300,
            data: function (params) { return { q: params.term }; },
            processResults: function (data) { return { results: data.results }; },
            cache: true
        },
        templateResult: function (u) {
            if (u.loading) return u.text;
            var $wrap = $('<div style="padding:2px 0;">');
            var $name = $('<div style="font-size:14px;font-weight:600;color:inherit;line-height:1.4;">').text(u.name || u.email);
            $wrap.append($name);
            if (u.name) {
                var $email = $('<div style="font-size:13px;color:inherit;opacity:0.75;margin-top:1px;">').text(u.email);
                $wrap.append($email);
            }
            return $wrap;
        },
        templateSelection: function (u) {
            return u.email ? (u.name ? u.name + '  〈' + u.email + '〉' : u.email) : u.text;
        }
    });

    $('#recipient-select').on('change', function () {
        var count = $(this).val() ? $(this).val().length : 0;
        var btn   = $('#send-selected-btn');
        var lbl   = $('#send-selected-label');
        if (count === 0) {
            btn.prop('disabled', true);
            lbl.text('Select recipients first');
        } else {
            btn.prop('disabled', false);
            lbl.text('Send to ' + count + ' recipient' + (count > 1 ? 's' : '') + ' now');
        }
    });

    $('#send-selected-form').on('submit', function () {
        var count = $('#recipient-select').val().length;
        return confirm('Send this campaign to ' + count + ' recipient' + (count > 1 ? 's' : '') + ' now?');
    });
});
</script>
@stop
