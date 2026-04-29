@extends('voyager::master')

@section('page_title', 'Email Campaigns')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i> Email Campaigns
    </h1>
    <a href="{{ route('email-campaigns.create') }}" class="btn btn-success btn-add-new">
        <i class="voyager-plus"></i> New Campaign
    </a>
    <a href="/admin/settings#marketing" class="btn btn-default" style="margin-left:6px;">
        <i class="voyager-settings"></i> Marketing Settings
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
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body" style="padding:0;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Campaign</th>
                                <th>Subject</th>
                                <th>Target Role</th>
                                <th>Status</th>
                                <th>Sent</th>
                                <th>Failed</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($campaigns as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td><strong>{{ $c->name }}</strong></td>
                                <td>{{ Str::limit($c->subject, 50) }}</td>
                                <td><span class="label label-default">{{ $c->target_role }}</span></td>
                                <td>
                                    @php
                                        $colors = ['draft'=>'default','sending'=>'warning','sent'=>'success','paused'=>'danger','scheduled'=>'info'];
                                    @endphp
                                    <span class="label label-{{ $colors[$c->status] ?? 'default' }}">{{ ucfirst($c->status) }}</span>
                                </td>
                                <td>{{ number_format($c->sent_count) }}</td>
                                <td>{{ number_format($c->failed_count) }}</td>
                                <td>{{ $c->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('email-campaigns.show', $c->id) }}" class="btn btn-sm btn-primary">
                                        <i class="voyager-eye"></i>
                                    </a>
                                    @if(!$c->isSent())
                                    <a href="{{ route('email-campaigns.edit', $c->id) }}" class="btn btn-sm btn-warning">
                                        <i class="voyager-edit"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('email-campaigns.preview', $c->id) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="voyager-browser"></i>
                                    </a>
                                    @if(!$c->isSent())
                                    <form method="POST" action="{{ route('email-campaigns.destroy', $c->id) }}" style="display:inline;" onsubmit="return confirm('Delete this campaign?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="voyager-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted" style="padding:40px;">No campaigns yet. <a href="{{ route('email-campaigns.create') }}">Create one</a>.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($campaigns->hasPages())
                <div class="panel-footer">
                    {{ $campaigns->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
