@extends('voyager::master')

@section('page_title', 'Recommended Campaign Setup')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-rocket"></i> Recommended Campaign Setup
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
                    Concrete settings to copy for common scenarios, and the exact server cron jobs needed to make
                    automations actually run. For how the scheduling mechanics work underneath, see the
                    <a href="{{ route('campaign-automations.guide') }}">How This Works</a> page.
                </div>

                {{-- Pre-flight checklist --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-check"></i> Before turning on a real campaign</h3></div>
                    <div class="panel-body">
                        <ol style="margin-bottom:0;">
                            <li>Use <strong>Preview Email</strong> and <strong>Send Test Email</strong> on the campaign — check it on both desktop and phone before anyone else sees it.</li>
                            <li>Confirm the <strong>SMTP Account</strong> on the campaign is the sender you actually want (Ayub / Ali Hassan / Zahid / contact).</li>
                            <li>Set <strong>Resend Gap (days)</strong> so this campaign doesn't hit someone another campaign already emailed recently.</li>
                            <li>Start with a small <strong>Batch Size</strong> (10–20) for the first day, watch the Send Log for failures, then increase once you've confirmed it's clean.</li>
                            <li>Set <strong>Daily Send Cap</strong> as a hard safety net even if you trust your Batch Size math — it protects you if you fat-finger a number later.</li>
                        </ol>
                    </div>
                </div>

                {{-- Recommended settings by scenario --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-list"></i> Recommended settings by scenario</h3></div>
                    <div class="panel-body">
                        <p class="text-muted">Based on our actual numbers: 66,107 <code>homey_client</code> users, Titan Business
                        plan (200/hour, 500/day per mailbox, no domain-wide cap across our 4 senders).</p>

                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead>
                                <tr>
                                    <th style="width:20%;">Scenario</th>
                                    <th>Frequency</th>
                                    <th>Batch Size</th>
                                    <th>Delay</th>
                                    <th>Daily Cap</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Quick internal test</td>
                                    <td>Once only</td>
                                    <td>5</td>
                                    <td>0s</td>
                                    <td>—</td>
                                    <td>Or just use <strong>Run Now</strong> — no need to save a real schedule for a one-off test.</td>
                                </tr>
                                <tr>
                                    <td>Full 66k list, one sender, gentle pacing</td>
                                    <td>Daily</td>
                                    <td>450</td>
                                    <td>~170s (spreads 450 across ~21hrs)</td>
                                    <td>500</td>
                                    <td>Stays under the 500/day mailbox cap with margin. At 450/day, covering the full list takes ~147 days (~5 months) on one sender.</td>
                                </tr>
                                <tr>
                                    <td>Full 66k list, split across all 4 senders</td>
                                    <td>Daily</td>
                                    <td>450 <em>(each of 4 automations)</em></td>
                                    <td>~170s <em>(each)</em></td>
                                    <td>500 <em>(each)</em></td>
                                    <td>4 separate campaigns + automations (one per sender). Combined ~1,800/day → full list in <strong>~37 days</strong> instead of ~5 months.</td>
                                </tr>
                                <tr>
                                    <td>Burst send, no pacing needed (small list)</td>
                                    <td>Once / Daily</td>
                                    <td>up to 200</td>
                                    <td>0s</td>
                                    <td>—</td>
                                    <td>Keep Batch Size ≤ 200 if Delay is 0 — that's the <em>hourly</em> cap, and with no delay the whole batch fires in under an hour.</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-muted" style="margin-top:10px;margin-bottom:0;">
                            Delay formula: <code>seconds = (hours to spread &times; 3600) &divide; batch size</code>.
                        </p>
                    </div>
                </div>

                {{-- Server cron setup --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-server"></i> Server cron jobs (Hostinger hPanel)</h3></div>
                    <div class="panel-body">
                        <p>Two separate cron entries are needed. Add both under <strong>hPanel → Advanced → Cron Jobs</strong>.</p>

                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th style="width:18%;">Job</th><th>Schedule</th><th>Command</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><strong>Scheduler</strong><br><span class="label label-success">already set up</span></td>
                                    <td>Once a day</td>
                                    <td><code>cd /home/USERNAME/domains/portal.webpenter.com/public_html && php artisan schedule:run</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Queue worker</strong><br><span class="label label-danger">needs adding</span></td>
                                    <td>Every minute</td>
                                    <td><code>cd /home/USERNAME/domains/portal.webpenter.com/public_html && php artisan queue:work --stop-when-empty --tries=3 --timeout=3600 >> /dev/null 2>&1</code></td>
                                </tr>
                            </tbody>
                        </table>

                        <p style="margin-top:10px;margin-bottom:0;">
                            Without the queue worker cron, automations get scheduled correctly but the emails just sit in
                            the queue forever — use the <strong>Process Queue Now</strong> button as a manual stopgap until
                            it's added.
                        </p>
                    </div>
                </div>

                {{-- Env requirement --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-settings"></i> Required <code>.env</code> setting</h3></div>
                    <div class="panel-body">
                        <p style="margin-bottom:0;">Production <code>.env</code> needs:</p>
                        <pre style="margin:10px 0 0;">QUEUE_CONNECTION=database</pre>
                        <p class="text-muted" style="margin-top:10px;margin-bottom:0;">
                            If this is still set to <code>sync</code>, everything runs immediately/inline instead of
                            through the queue worker — sends will still go out, but a manual Run Now or a big daily batch
                            can tie up the request/cron process for its full duration instead of being handled in the
                            background.
                        </p>
                    </div>
                </div>

                {{-- Verifying it's working --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-eye"></i> How to check it's actually working</h3></div>
                    <div class="panel-body">
                        <ul style="margin-bottom:0;">
                            <li>An automation's <strong>Send Log</strong> (on its show page) fills in with sent/failed rows after each run.</li>
                            <li>If sends seem stuck, click <strong>Process Queue Now</strong> — if that immediately clears a backlog, the queue worker cron isn't running and needs to be added (see above).</li>
                            <li>Check <code>storage/logs/laravel.log</code> for lines starting with <code>[Automations]</code> — every step is logged, including why a run was skipped (daily cap reached, no eligible recipients, etc.).</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
