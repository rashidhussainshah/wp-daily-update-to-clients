@extends('voyager::master')

@php $editing = isset($account); @endphp

@section('page_title', $editing ? 'Edit SMTP Account' : 'Add SMTP Account')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i> {{ $editing ? 'Edit: ' . $account->name : 'Add SMTP Account' }}
    </h1>
</div>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-7 col-md-offset-2">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ $editing ? route('smtp-accounts.update', $account->id) : route('smtp-accounts.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Account Details</h3></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label>Display Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $account->name ?? '') }}"
                                   placeholder="e.g. Ayub — ayub@webpenter.com" required>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>From Address <span class="text-danger">*</span></label>
                                    <input type="email" name="from_address" class="form-control"
                                           value="{{ old('from_address', $account->from_address ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>From Name <span class="text-danger">*</span></label>
                                    <input type="text" name="from_name" class="form-control"
                                           value="{{ old('from_name', $account->from_name ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label" style="font-weight:normal;">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}>
                                Active (available for use in campaigns)
                            </label>
                        </div>

                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">SMTP Settings</h3></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label>SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" name="host" class="form-control"
                                   value="{{ old('host', $account->host ?? 'smtp.titan.email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Port <span class="text-danger">*</span></label>
                                    <input type="number" name="port" class="form-control"
                                           value="{{ old('port', $account->port ?? 465) }}" min="1" max="65535" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Encryption <span class="text-danger">*</span></label>
                                    <select name="encryption" class="form-control" required>
                                        @foreach(['ssl','tls','starttls'] as $enc)
                                            <option value="{{ $enc }}"
                                                {{ old('encryption', $account->encryption ?? 'ssl') === $enc ? 'selected' : '' }}>
                                                {{ strtoupper($enc) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Username (email) <span class="text-danger">*</span></label>
                            <input type="email" name="username" class="form-control"
                                   value="{{ old('username', $account->username ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Password {{ $editing ? '(leave blank to keep current)' : '' }} <span class="text-danger">{{ $editing ? '' : '*' }}</span></label>
                            <input type="password" name="password" class="form-control"
                                   autocomplete="new-password"
                                   {{ $editing ? '' : 'required' }}>
                        </div>

                    </div>
                </div>

                <div style="margin-bottom:32px;">
                    <button type="submit" class="btn btn-success">
                        <i class="voyager-check"></i> {{ $editing ? 'Update Account' : 'Save Account' }}
                    </button>
                    <a href="{{ route('smtp-accounts.index') }}" class="btn btn-default">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@stop
