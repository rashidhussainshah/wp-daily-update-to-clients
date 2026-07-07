@extends('voyager::master')

@section('page_title', 'SMTP Accounts')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-mail"></i> SMTP Accounts</h1>
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

    <div class="panel panel-bordered">
        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 class="panel-title">Configured Accounts</h3>
            <a href="{{ route('smtp-accounts.create') }}" class="btn btn-success btn-sm">
                <i class="voyager-plus"></i> Add Account
            </a>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="table table-hover" style="margin:0;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Host / Port</th>
                        <th>Username</th>
                        <th>From Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td><strong>{{ $account->name }}</strong></td>
                        <td>{{ $account->host }}:{{ $account->port }} ({{ $account->encryption }})</td>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->from_address }} <span class="text-muted">/ {{ $account->from_name }}</span></td>
                        <td>
                            <span class="badge badge-{{ $account->is_active ? 'success' : 'danger' }}">
                                {{ $account->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                {{-- Send test --}}
                                <button class="btn btn-info"
                                        onclick="openTestModal({{ $account->id }}, '{{ addslashes($account->name) }}')"
                                        title="Send test email">
                                    <i class="voyager-paper-plane"></i>
                                </button>
                                <a href="{{ route('smtp-accounts.edit', $account->id) }}" class="btn btn-primary" title="Edit">
                                    <i class="voyager-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('smtp-accounts.destroy', $account->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" title="Delete"
                                            onclick="return confirm('Delete {{ addslashes($account->name) }}?')">
                                        <i class="voyager-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding:40px;">
                            No SMTP accounts yet. <a href="{{ route('smtp-accounts.create') }}">Add one</a>.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Test email modal --}}
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="testForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Test Email — <span id="modalAccountName"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Send test to</label>
                        <input type="email" name="test_email" class="form-control"
                               value="{{ auth()->user()->email }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Send Test</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('javascript')
<script>
function openTestModal(id, name) {
    document.getElementById('testForm').action = '/admin/smtp-accounts/' + id + '/send-test';
    document.getElementById('modalAccountName').textContent = name;
    $('#testModal').modal('show');
}
</script>
@stop
