@extends('voyager::master')
@section('page_title', 'Email Signatures')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title"><i class="voyager-edit-copy"></i> Email Signatures</h1>
    <a href="{{ route('email-signatures.create') }}" class="btn btn-success btn-add-new">
        <i class="voyager-plus"></i> New Signature
    </a>
    <a href="{{ route('email-campaigns.index') }}" class="btn btn-default btn-sm" style="margin-left:6px;">
        <i class="voyager-mail"></i> Campaigns
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

<div class="panel panel-bordered" style="margin-bottom:0;">
    <div class="panel-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead>
                <tr>
                    <th style="width:44px;"></th>
                    <th>Sender Email</th>
                    <th>Name / Title</th>
                    <th>Template</th>
                    <th>Contact</th>
                    <th style="text-align:center;">Active</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($signatures as $sig)
                <tr>
                    <td style="vertical-align:middle;padding:10px 12px;">
                        @if($sig->photo_url)
                            <img src="{{ $sig->photo_url }}" width="36" height="36"
                                 style="border-radius:50%;object-fit:cover;display:block;">
                        @else
                            <div style="width:36px;height:36px;border-radius:50%;background:#e2e8f0;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:15px;font-weight:700;color:#94a3b8;">
                                {{ strtoupper(substr($sig->display_name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td style="vertical-align:middle;">
                        <span style="font-family:monospace;font-size:13px;">{{ $sig->sender_email }}</span>
                    </td>
                    <td style="vertical-align:middle;">
                        <strong>{{ $sig->display_name }}</strong>
                        @if($sig->designation)
                            <div style="font-size:12px;color:#888;">{{ $sig->designation }}</div>
                        @endif
                    </td>
                    <td style="vertical-align:middle;">
                        @php $colors = ['classic'=>'primary','minimal'=>'default','bold'=>'warning']; @endphp
                        <span class="label label-{{ $colors[$sig->template] ?? 'default' }}">
                            {{ ucfirst($sig->template) }}
                        </span>
                        <div style="display:inline-block;width:14px;height:14px;border-radius:3px;
                                    background:{{ $sig->accent_color }};vertical-align:middle;margin-left:6px;"></div>
                    </td>
                    <td style="vertical-align:middle;font-size:12px;color:#666;">
                        @if($sig->phone){{ $sig->phone }}<br>@endif
                        @if($sig->contact_email)<span style="color:#0066cc;">{{ $sig->contact_email }}</span>@endif
                    </td>
                    <td style="vertical-align:middle;text-align:center;">
                        @if($sig->is_active)
                            <span class="label label-success">Yes</span>
                        @else
                            <span class="label label-default">No</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle;text-align:right;white-space:nowrap;">
                        <a href="{{ route('email-signatures.preview', $sig->id) }}" target="_blank"
                           class="btn btn-sm btn-info" title="Preview">
                            <i class="voyager-browser"></i>
                        </a>
                        <a href="{{ route('email-signatures.edit', $sig->id) }}"
                           class="btn btn-sm btn-warning" title="Edit">
                            <i class="voyager-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('email-signatures.destroy', $sig->id) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Delete signature for {{ $sig->sender_email }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Delete">
                                <i class="voyager-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted" style="padding:40px;">
                        No signatures yet.
                        <a href="{{ route('email-signatures.create') }}">Create one</a>.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-bordered" style="margin-top:20px;border-left:4px solid #0ea5e9;">
    <div class="panel-body">
        <strong><i class="voyager-info-circled"></i> How it works</strong>
        <p style="margin:8px 0 0;font-size:13px;color:#666;line-height:1.6;">
            Each signature is matched to campaigns by <strong>Sender Email</strong> (must match the campaign's From Email exactly).
            When a campaign is sent, the active signature for that sender is automatically appended to every email.
            Set <strong>Active = No</strong> to send campaigns without a signature.
        </p>
    </div>
</div>

</div>
@stop
