@extends('voyager::master')

@section('page_title', 'How Campaign Automation Works')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-info-circled"></i> How Campaign Automation Works
        <a href="{{ route('campaign-automations.index') }}" class="btn btn-warning">
            <i class="voyager-mail"></i> Go to Campaign Automations
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="alert alert-info" style="padding:10px 15px;">
                    A plain-language walkthrough of how to set up an automation, what each setting actually does,
                    and how to schedule sends so they trickle out through the day instead of arriving in one burst.
                </div>

                {{-- Step by step --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-list"></i> Setting up a new automation, step by step</h3></div>
                    <div class="panel-body">
                        <ol style="margin-bottom:0;">
                            <li>Make sure the <strong>Email Campaign</strong> you want to send already exists (Admin → Email Campaigns) — the automation just schedules an existing campaign, it doesn't write the email content itself.</li>
                            <li>Go to <strong>Campaign Automations → New Automation</strong>.</li>
                            <li>Pick the campaign, the target role (who receives it), and a <strong>Frequency</strong> (see below).</li>
                            <li>Set <strong>Batch Size</strong> and <strong>Delay Between Emails</strong> together — this pair controls how many go out and how spread-out they are (worked example below).</li>
                            <li>Save. The automation shows as <span class="label label-success">active</span> and will run on its own from here — no need to touch it again unless you want to pause, edit, or check it.</li>
                            <li>To send a small test batch right now instead of waiting for the schedule, use <strong>Run Now</strong> on the automation's page.</li>
                        </ol>
                    </div>
                </div>

                {{-- Frequency --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-calendar"></i> Frequency — how often a new batch starts</h3></div>
                    <div class="panel-body">
                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th style="width:20%;">Option</th><th>What it means</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><span class="label label-default">Once only</span></td>
                                    <td>Sends exactly one batch (up to Batch Size recipients), then the automation marks itself <strong>Completed</strong> and stops.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-primary">Daily</span></td>
                                    <td>Starts a new batch every day at <strong>Send Time</strong>.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-primary">Weekly</span></td>
                                    <td>Starts a new batch once a week, on the chosen <strong>Day of Week</strong>, at <strong>Send Time</strong>.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-primary">Monthly</span></td>
                                    <td>Starts a new batch once a month, on the chosen <strong>Day of Month</strong>, at <strong>Send Time</strong>.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- The two moving parts --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-refresh"></i> The two things running behind the scenes</h3></div>
                    <div class="panel-body">
                        <p>Two separate, unrelated processes make an automation actually work. You don't need to operate either
                        one day-to-day, but it helps to know they exist if a send seems delayed:</p>
                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th style="width:22%;">Process</th><th>What it does</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><strong>Scheduler</strong></td>
                                    <td>Runs once a day. Checks every automation's Frequency/Send Time, and for anything due, decides who the recipients are and hands that batch off — this step is fast, no emails are sent yet.</td>
                                </tr>
                                <tr>
                                    <td><strong>Queue worker</strong></td>
                                    <td>Picks up the batch the scheduler handed off and does the actual slow part — connecting to the mail server and sending each email, waiting your configured Delay between each one.</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="margin-top:10px;margin-bottom:0;" class="text-muted">
                            If sends seem stuck, use <strong>Process Queue Now</strong> on the automations list — it manually runs
                            the queue worker step for whatever's currently waiting.
                        </p>
                    </div>
                </div>

                {{-- Batch size + delay worked example --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-clock"></i> Spreading sends across the day</h3></div>
                    <div class="panel-body">
                        <p>The scheduler only checks automations <strong>once a day</strong>, so "Daily" frequency means one batch
                        starts per day — it does not mean the app checks multiple times a day. To send only a few emails at a time
                        while still getting many out over the course of a day, use <strong>Batch Size</strong> and <strong>Delay Between
                        Emails</strong> together: the whole batch is sent within that one daily run, one email at a time, waiting
                        the delay between each — so a longer delay spreads the same batch across more hours instead of sending it
                        all in a burst.</p>

                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th>Goal</th><th>Batch Size</th><th>Delay Between Emails</th><th>Result</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>Send 30 emails spread across an 8-hour work day</td>
                                    <td>30</td>
                                    <td>960s (16 min)</td>
                                    <td>~1 email every 16 minutes for ~8 hours</td>
                                </tr>
                                <tr>
                                    <td>Send 10 emails spread across the whole day</td>
                                    <td>10</td>
                                    <td>2600s (~43 min)</td>
                                    <td>~1 email every 43 minutes for ~24 hours</td>
                                </tr>
                                <tr>
                                    <td>Send everything at once, no spam-filter pacing needed</td>
                                    <td>however many you want</td>
                                    <td>0</td>
                                    <td>Whole batch sent back-to-back with no wait</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-muted" style="margin-top:10px;margin-bottom:0;">
                            Formula: <code>delay (seconds) = desired spread (seconds) &divide; batch size</code>.
                            Example: an 8-hour spread is 28,800 seconds; divided across 30 emails is 960 seconds each.
                        </p>
                    </div>
                </div>

                {{-- Other settings --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-settings"></i> Other settings</h3></div>
                    <div class="panel-body">
                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th style="width:22%;">Setting</th><th>What it does</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><strong>Daily Send Cap</strong></td>
                                    <td>Hard ceiling on how many this automation can send in one calendar day, even if Batch Size is higher — a safety net independent of the spreading strategy above.</td>
                                </tr>
                                <tr>
                                    <td><strong>Resend Gap (days)</strong></td>
                                    <td>Prevents the same client getting emails from multiple different campaigns too close together. Applies across <strong>all</strong> campaigns, not just this one — e.g. a value of 30 means once a client gets any campaign email, no campaign will email them again for 30 days.</td>
                                </tr>
                                <tr>
                                    <td><strong>Skip Weekends</strong></td>
                                    <td>If today is a scheduled send day but falls on Saturday/Sunday, pushes the batch to the next Monday instead.</td>
                                </tr>
                                <tr>
                                    <td><strong>Notify Email</strong></td>
                                    <td>Sends a summary email to this address when a "Once only" automation finishes its single batch.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
