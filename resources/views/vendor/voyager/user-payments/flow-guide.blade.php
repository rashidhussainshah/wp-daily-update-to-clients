@extends('voyager::master')

@section('page_title', 'Payment Request Flow')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-info-circled"></i> Payment Request Flow
        <a href="{{ route('voyager.user-payments.index') }}" class="btn btn-warning">
            <i class="voyager-dollar"></i> Go to Payment Requests
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="alert alert-info" style="padding:10px 15px;">
                    How a payment request moves from creation to approval. The payout (paid) stage
                    will be defined separately. All amounts are calculated by the system — nobody
                    calculates anything by hand.
                </div>

                {{-- Roles --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-people"></i> Who does what</h3></div>
                    <div class="panel-body">
                        <table class="table table-condensed" style="margin-bottom:0;">
                            <thead><tr><th style="width:30%;">Role</th><th>Responsibility</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><span class="label label-success">Development Partner</span></td>
                                    <td>Submits payment requests for project earnings.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-primary">Business Developer</span></td>
                                    <td>Commission request is created <strong>automatically</strong> for partner projects; submits manually ONLY for salary-employee projects.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-warning">Accountant</span></td>
                                    <td>Records incomes, verifies requests, sets the PKR rate and approves.</td>
                                </tr>
                                <tr>
                                    <td><span class="label label-danger">Administrator</span></td>
                                    <td>Edits and deletes requests; oversight only — does not approve or mark paid.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Stage 0 --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-download"></i> Before you submit</h3></div>
                    <div class="panel-body">
                        <ol style="margin-bottom:0;">
                            <li>The client income must already be recorded in the <strong>Incomes</strong> module with the correct source, order date, transaction id and conversion rate. Only the accountant / administrator records incomes, on the Incomes page.</li>
                            <li>The Project and Project Target should exist. If not, use the <strong>quick-add</strong> buttons on the request form — no need to leave the page.</li>
                        </ol>
                    </div>
                </div>

                {{-- Stage 1 --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-plus"></i> Stage 1 — Development Partner submits a request</h3></div>
                    <div class="panel-body">
                        <ol>
                            <li>Go to <strong>Payment Requests &rarr; Add New</strong>.</li>
                            <li><strong>Select the Income first</strong> — the client source, earning amount and conversion rate fill in automatically from it.</li>
                            <li>Complete the rest: <strong>Project</strong>, <strong>Project Target</strong> and the <strong>Business Developer</strong> who brought the client. Adjust the earning amount only if your work was part of the income, not all of it.</li>
                            <li>Submit. The system calculates everything <strong>immediately</strong>, before anyone reviews it:
                                <ul>
                                    <li>The platform fee is deducted automatically based on the source.</li>
                                    <li>Your share is calculated automatically from the net amount — anything typed in the payable field is ignored.</li>
                                    <li>The PKR payable is also calculated right away, using the income's own conversion rate.</li>
                                    <li>The <strong>Business Developer's commission request is created automatically</strong> and linked to your request (shown with an <span class="label" style="background:#8492a6;">Auto</span> badge in the listing).</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="alert alert-warning" style="margin-bottom:0;padding:10px 15px;">
                            <strong>The system will reject the request if:</strong>
                            <ul style="margin-bottom:5px;">
                                <li>You already have a request against the same income (<strong>one request per person per income</strong>).</li>
                                <li>The requests against that income would exceed the income amount.</li>
                                <li>Income, project, target, source or earning amount is missing.</li>
                            </ul>
                            If you see a rejection message, do not retry with changed numbers — contact the accountant.
                        </div>
                    </div>
                </div>

                {{-- Stage 2 --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-person"></i> Stage 2 — Business Developer manual request (salary-employee only)</h3></div>
                    <div class="panel-body">
                        <ul style="margin-bottom:0;">
                            <li>Submit a manual request ONLY when the project was delivered by <strong>salaried staff</strong> (no development partner is involved).</li>
                            <li>The form works the same way — select the income and the system applies your commission automatically, marking the request as <em>salary employee</em>.</li>
                            <li><strong>Never submit a manual commission request for a partner project.</strong> The system already created it when the partner submitted — a second one is a duplicate and will be blocked.</li>
                        </ul>
                    </div>
                </div>

                {{-- Stage 3 --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-check"></i> Stage 3 — Verification &amp; Approval (Accountant)</h3></div>
                    <div class="panel-body">
                        <ol style="margin-bottom:0;">
                            <li>Open <strong>Payment Requests</strong>. A partner request and its auto-generated commission appear <strong>grouped as a pair</strong>, with a warning if the pair's states don't match.</li>
                            <li>Verify: the income (click it for a quick-view, or open the full income record to see every request taken from it), project/target, amount and source.</li>
                            <li>Click <strong>Rate &amp; Approve</strong> — the PKR rate is pre-filled from the income's conversion rate (editable). The system recalculates the payable in PKR and approves; the linked commission is approved automatically with the same rate. Email notifications are sent.</li>
                            <li><strong>Only the Accountant can approve</strong> a request — Administrators can no longer approve. <strong>Only an Administrator can edit or delete</strong> a request (deleting a partner request also deletes its auto-generated commission).</li>
                        </ol>
                    </div>
                </div>

                {{-- Stage 4 --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-dollar"></i> Stage 4 — Payment (payout)</h3></div>
                    <div class="panel-body">
                        <p style="margin-bottom:0;">Once approved, marking a request as paid is handled by a single designated person — not the Accountant or Administrator generally. If your approved request isn't marked paid yet, that's expected; there's nothing further for you to do.</p>
                    </div>
                </div>

                {{-- Rules --}}
                <div class="panel panel-bordered panel-warning">
                    <div class="panel-heading"><h3 class="panel-title"><i class="voyager-warning"></i> Rules at a glance</h3></div>
                    <div class="panel-body">
                        <ol style="margin-bottom:0;">
                            <li>Record the income first, then request against it.</li>
                            <li>One request per person per income — duplicates are blocked.</li>
                            <li>Your share and the PKR payable are calculated by the system the moment you submit — never hand-calculate, and nothing typed into those fields is used.</li>
                            <li>Commission for partner projects is automatic — never submit it manually.</li>
                            <li>Manual business-developer requests are only for salary-employee projects.</li>
                            <li>One Rate &amp; Approve action approves both sides of a pair.</li>
                            <li>Only the Accountant approves requests. Only an Administrator can edit or delete one.</li>
                            <li>Blocked request? Contact the accountant — don't work around it.</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
