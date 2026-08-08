@extends('voyager::master')

@php $editing = isset($automation); @endphp

@section('page_title', $editing ? 'Edit Automation' : 'New Automation')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i>
        {{ $editing ? 'Edit: ' . $automation->name : 'New Campaign Automation' }}
    </h1>
</div>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ $editing ? route('campaign-automations.update', $automation->id) : route('campaign-automations.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">General</h3></div>
                    <div class="panel-body">

                        {{-- Name --}}
                        <div class="form-group">
                            <label>Automation Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $automation->name ?? '') }}"
                                   placeholder="e.g. Homey clients welcome series" required>
                        </div>

                        {{-- Campaign --}}
                        <div class="form-group">
                            <label>Campaign <span class="text-danger">*</span></label>
                            <select name="campaign_id" class="form-control" required>
                                <option value="">— select campaign —</option>
                                @foreach($campaigns as $c)
                                    <option value="{{ $c->id }}"
                                        {{ old('campaign_id', $automation->campaign_id ?? '') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Target role --}}
                        <div class="form-group">
                            <label>Target Role <span class="text-danger">*</span></label>
                            <select name="target_role" class="form-control" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('target_role', $automation->target_role ?? 'homey_client') === $role->name ? 'selected' : '' }}>
                                        {{ $role->display_name ?: $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Schedule</h3></div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Frequency <span class="text-danger">*</span></label>
                                    <select name="frequency" id="frequency" class="form-control" required>
                                        @foreach(['once'=>'Once only','daily'=>'Daily','weekly'=>'Weekly','monthly'=>'Monthly'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('frequency', $automation->frequency ?? 'once') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Send Time <span class="text-danger">*</span></label>
                                    <input type="time" name="send_time" class="form-control"
                                           value="{{ old('send_time', isset($automation) ? substr($automation->send_time, 0, 5) : '09:00') }}"
                                           required>
                                    <span class="help-block">Server time (Pakistan = UTC+5)</span>
                                </div>
                            </div>
                        </div>

                        {{-- Weekly day --}}
                        <div class="form-group" id="row-dow" style="display:none;">
                            <label>Day of Week</label>
                            <select name="send_day_of_week" class="form-control">
                                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $i => $day)
                                    <option value="{{ $i }}"
                                        {{ old('send_day_of_week', $automation->send_day_of_week ?? 1) == $i ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Monthly day --}}
                        <div class="form-group" id="row-dom" style="display:none;">
                            <label>Day of Month</label>
                            <select name="send_day_of_month" class="form-control">
                                @for($d = 1; $d <= 28; $d++)
                                    <option value="{{ $d }}"
                                        {{ old('send_day_of_month', $automation->send_day_of_month ?? 1) == $d ? 'selected' : '' }}>
                                        {{ $d }}
                                    </option>
                                @endfor
                            </select>
                            <span class="help-block">Max 28 to avoid month-end issues</span>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control"
                                           value="{{ old('start_date', isset($automation) ? $automation->start_date->toDateString() : now()->toDateString()) }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>End Date <span class="text-muted">(optional)</span></label>
                                    <input type="date" name="end_date" class="form-control"
                                           value="{{ old('end_date', isset($automation) && $automation->end_date ? $automation->end_date->toDateString() : '') }}">
                                    <span class="help-block">Leave blank to run indefinitely</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Sending Limits</h3></div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Batch Size (emails per schedule) <span class="text-danger">*</span></label>
                                    <input type="number" name="batch_size" class="form-control"
                                           value="{{ old('batch_size', $automation->batch_size ?? 5) }}"
                                           min="1" max="500" required>
                                    <span class="help-block">Total emails to send per scheduled run</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Resend Gap (days)</label>
                                    <input type="number" name="resend_gap_days" class="form-control"
                                           value="{{ old('resend_gap_days', $automation->resend_gap_days ?? 0) }}"
                                           min="0">
                                    <span class="help-block">
                                        Applies <strong>across all campaigns &amp; all senders</strong> — not just this one.<br>
                                        <strong>0</strong> — once a client is emailed by <em>any</em> campaign, they will never be emailed again by any campaign.<br>
                                        <strong>30</strong> — if Ayub emailed client X on July 1, no campaign (Ayub's or Ali Hassan's) will email client X again until July 31.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Delay Between Emails (seconds)</label>
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="number" name="email_delay_seconds" id="email_delay_seconds"
                                           class="form-control"
                                           value="{{ old('email_delay_seconds', $automation->email_delay_seconds ?? 0) }}"
                                           min="0">
                                </div>
                                <div class="col-sm-8" style="padding-top:7px;">
                                    <span class="text-muted" id="delay-hint">
                                        @php
                                            $d = old('email_delay_seconds', $automation->email_delay_seconds ?? 0);
                                        @endphp
                                        @if($d > 0)
                                            = {{ $d >= 60 ? floor($d/60).'m '.($d%60 ? $d%60 .'s' : '') : $d.'s' }} between emails
                                        @else
                                            0 = all emails sent at once per run
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <span class="help-block">
                                Recommended: <strong>60</strong> (1 min) or <strong>120</strong> (2 min) to avoid spam filters.
                                The whole batch is sent on its scheduled day, one email at a time, waiting this many
                                seconds between each — so a bigger delay spreads the same batch across more of the day
                                instead of sending it all in a burst. See the
                                <a href="{{ route('campaign-automations.guide') }}" style="font-weight:600;text-decoration:underline;">How This Works guide</a>
                                for a worked example.
                            </span>
                        </div>

                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Advanced Settings <small style="font-weight:normal;color:#888;">(all optional)</small></h3>
                    </div>
                    <div class="panel-body">

                        {{-- Skip Weekends --}}
                        <div class="form-group">
                            <label style="font-weight:600;">Skip Weekends</label>
                            <div style="margin-top:6px;">
                                <label style="font-weight:normal;cursor:pointer;">
                                    <input type="checkbox" name="skip_weekends" value="1"
                                        {{ old('skip_weekends', $automation->skip_weekends ?? false) ? 'checked' : '' }}>
                                    &nbsp;Do not send on Saturday or Sunday
                                </label>
                            </div>
                            <p class="help-block">
                                If checked, any run that falls on a weekend is automatically skipped and rescheduled
                                to the next Monday at the same send time. Best for B2B outreach — decision makers
                                rarely act on cold emails over the weekend.
                            </p>
                        </div>

                        <hr style="margin:16px 0;">

                        {{-- Daily Send Capacity --}}
                        <div class="form-group">
                            <label style="font-weight:600;">Daily Send Capacity</label>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="number" name="daily_send_cap" class="form-control"
                                           value="{{ old('daily_send_cap', $automation->daily_send_cap ?? '') }}"
                                           min="1" max="9999" placeholder="e.g. 50">
                                </div>
                                <div class="col-sm-9" style="padding-top:8px;color:#888;font-size:13px;">
                                    emails per day &nbsp;·&nbsp; leave blank for no limit
                                </div>
                            </div>
                            <p class="help-block">
                                Maximum emails this automation can send in one calendar day across all its runs.
                                Titan SMTP allows ~200–300 emails/day per account — setting <strong>50</strong>
                                keeps you well within limits and avoids spam flags.
                                Once the cap is reached, the scheduler skips the rest of that day automatically.
                            </p>
                        </div>

                        <hr style="margin:16px 0;">

                        {{-- Notify on Completion --}}
                        <div class="form-group">
                            <label style="font-weight:600;">Notify on Completion</label>
                            <input type="email" name="notify_email" class="form-control"
                                   value="{{ old('notify_email', $automation->notify_email ?? '') }}"
                                   placeholder="e.g. ayub@webpenter.com">
                            <p class="help-block">
                                When this automation finishes all sends and is marked <em>completed</em>,
                                a summary email is sent to this address showing: automation name, campaign name,
                                total emails sent, total failed, and the completion time.
                                Leave blank to skip the notification.
                            </p>
                        </div>

                    </div>
                </div>

                <div style="margin-bottom:32px;">
                    <button type="submit" class="btn btn-success">
                        <i class="voyager-check"></i> {{ $editing ? 'Update Automation' : 'Create & Schedule' }}
                    </button>
                    <a href="{{ route('campaign-automations.index') }}" class="btn btn-default">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
(function () {
    var freq = document.getElementById('frequency');
    var rowDow = document.getElementById('row-dow');
    var rowDom = document.getElementById('row-dom');

    function toggle() {
        rowDow.style.display = freq.value === 'weekly'  ? '' : 'none';
        rowDom.style.display = freq.value === 'monthly' ? '' : 'none';
    }

    freq.addEventListener('change', toggle);
    toggle();

    // Delay hint
    var delayInput = document.getElementById('email_delay_seconds');
    var delayHint  = document.getElementById('delay-hint');
    function updateDelayHint() {
        var secs = parseInt(delayInput.value) || 0;
        if (secs <= 0) {
            delayHint.textContent = '0 = all emails sent at once per run';
        } else if (secs < 60) {
            delayHint.textContent = '= ' + secs + 's between emails';
        } else {
            var m = Math.floor(secs / 60), s = secs % 60;
            delayHint.textContent = '= ' + m + 'm' + (s ? ' ' + s + 's' : '') + ' between emails';
        }
    }
    delayInput.addEventListener('input', updateDelayHint);
})();
</script>
@stop
