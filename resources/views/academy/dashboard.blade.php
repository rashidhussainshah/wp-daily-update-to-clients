<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>My Learning Journey</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --line:#e2e8f0; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); }
    .wrap{ max-width:960px; margin:0 auto; padding:40px 24px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    .row2{ display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .status{ padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; }
    .paid{ background:var(--g-100); color:var(--g-700); }
    .due{ background:#fef3c7; color:#b45309; }
    .skill{ display:flex; align-items:center; gap:10px; padding:9px 0; border-bottom:1px solid var(--line); }
    .skill:last-child{ border:0; }
    .skill input{ width:18px; height:18px; margin:0; flex-shrink:0; accent-color:var(--g-600); }
    .stage-title{ font-weight:700; color:var(--ink); font-size:14px; }
    .stage-sub{ color:var(--muted); font-size:12px; margin-top:2px; }
    .stage-tabs{ display:flex; flex-wrap:wrap; gap:8px; margin:14px 0 18px; }
    .stage-tab{ display:flex; align-items:center; gap:8px; padding:8px 14px 8px 8px; border-radius:999px; border:1.5px solid var(--line); background:var(--white); cursor:pointer; font-family:inherit; font-size:12.5px; font-weight:600; color:var(--ink-soft); }
    .stage-tab.active{ border-color:var(--g-600); background:var(--g-50); color:var(--g-700); }
    .stage-tab .stage-dot{ width:20px; height:20px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:10.5px; font-weight:800; color:#fff; background:var(--line); }
    .stage-tab.done .stage-dot{ background:var(--g-600); }
    .stage-tab.current .stage-dot{ background:var(--g-600); }
    .stage-panel-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding-bottom:14px; border-bottom:1px solid var(--line); margin-bottom:14px; }
    .stage-flow-hint{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:10px 14px; border-radius:10px; font-size:12.5px; margin-bottom:16px; }
    .status-locked{ background:var(--line); color:var(--muted); }
    .week-group{ margin-bottom:16px; }
    .week-group:last-child{ margin-bottom:0; }
    .week-label{ font-size:11.5px; font-weight:800; color:var(--g-700); text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
    .skill-locked{ opacity:.55; }
    .stage-projects{ margin-top:16px; padding-top:14px; border-top:1px solid var(--line); }
    .stage-projects ul{ margin:8px 0 0; padding-left:18px; font-size:13px; color:var(--ink-soft); }
    .stage-projects li{ margin-bottom:6px; }
    .project-steps-link{ background:none; border:0; color:var(--g-700); font-weight:700; font-size:11.5px; text-decoration:underline; cursor:pointer; font-family:inherit; margin-left:8px; padding:0; }
    .project-steps-list{ margin:12px 0 0; padding-left:20px; font-size:13.5px; color:var(--ink-soft); }
    .project-steps-list li{ margin-bottom:8px; line-height:1.5; }
    .stage-submit-form{ margin-top:20px; padding-top:16px; border-top:1px solid var(--line); }
    .level-badge{ display:inline-flex; align-items:center; gap:7px; padding:6px 14px; border-radius:999px; font-weight:800; font-size:12.5px; margin-top:12px; position:relative; overflow:hidden; }
    .level-badge .icon{ font-size:14px; }
    .level-1{ background:var(--g-50); color:var(--g-700); border:1.5px solid var(--g-100); }
    .level-2{ background:#eff6ff; color:#1d4ed8; border:1.5px solid #dbeafe; }
    .level-3{ background:#f5f3ff; color:#6d28d9; border:1.5px solid #ede9fe; }
    .level-4{ background:#fff7ed; color:#c2410c; border:1.5px solid #ffedd5; }
    .level-5{ background:linear-gradient(135deg,#fef9c3,#fde68a); color:#92400e; border:1.5px solid #fcd34d; animation:badge-glow 2.5s ease-in-out infinite; }
    .level-5::after{ content:''; position:absolute; top:0; left:-60%; width:35%; height:100%; background:linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent); animation:badge-shine 3s ease-in-out infinite; }
    @keyframes badge-glow{ 0%,100%{ box-shadow:0 0 0 0 rgba(217,119,6,.22); } 50%{ box-shadow:0 0 0 5px rgba(217,119,6,0); } }
    @keyframes badge-shine{ 0%{ left:-60%; } 60%,100%{ left:120%; } }
    .btn{ display:inline-block; padding:11px 20px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); }
    input,textarea{ width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; margin-bottom:12px; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .top-bar{ display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .top-bar .help-link{ color:var(--g-700); font-size:13px; font-weight:600; text-decoration:none; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .modal-overlay{ display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); align-items:center; justify-content:center; z-index:50; padding:20px; }
    .modal-overlay.open{ display:flex; }
    .modal-box{ background:var(--white); border-radius:16px; padding:24px; max-width:520px; width:100%; max-height:90vh; overflow-y:auto; }
    .modal-box h3{ margin:0 0 4px; font-size:18px; color:var(--ink); }
    .modal-box .hint{ color:var(--muted); font-size:12.5px; margin-bottom:16px; }
    .modal-box label{ display:block; font-size:12.5px; font-weight:700; color:#334155; margin-bottom:6px; }
    .modal-actions{ display:flex; justify-content:flex-end; gap:10px; margin-top:6px; }
    @media (max-width: 640px){
        .wrap{ padding:24px 16px; }
        .row2{ grid-template-columns:1fr; }
    }
</style>
</head>
<body>
<div class="wrap">
    <div class="top-bar">
        <a href="{{ route('academy.help') }}" class="help-link">❓ Help</a>
        <form class="logout-form" method="POST" action="{{ route('voyager.logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif

    <div class="card">
        <h1>My Learning Journey</h1>
        <div class="sub">{{ Auth::user()->name }} &middot; {{ $enrollment->track->name }} &middot; Stage {{ $enrollment->current_stage + 1 }} of {{ count($stages) }} &middot; {{ $enrollment->progressPercent() }}% complete</div>
        @php $level = $enrollment->levelTier(); @endphp
        <div class="level-badge level-{{ $level['tier'] }}">
            <span class="icon">{{ $level['icon'] }}</span> {{ $level['label'] }}
        </div>
    </div>

    <div class="card">
        <strong>Track Stages</strong>
        <div class="stage-tabs">
            @foreach($stages as $i => $stage)
                @php $tabState = $i < $enrollment->current_stage ? 'done' : ($i === $enrollment->current_stage ? 'current' : 'upcoming'); @endphp
                <button type="button" class="stage-tab {{ $tabState }} {{ $i === $enrollment->current_stage ? 'active' : '' }}" onclick="showStage({{ $i }})" id="tab-btn-{{ $i }}">
                    <span class="stage-dot">{{ $tabState === 'done' ? '✓' : $i + 1 }}</span>
                    <span>{{ $stage['title'] }}</span>
                </button>
            @endforeach
        </div>

        @foreach($stages as $i => $stage)
            @php
                $panelState = $i < $enrollment->current_stage ? 'done' : ($i === $enrollment->current_stage ? 'current' : 'upcoming');
                $stageSkillProgress = $allProgress[$i]['skills'] ?? [];
            @endphp
            <div class="stage-panel" id="stage-panel-{{ $i }}" style="{{ $i === $enrollment->current_stage ? '' : 'display:none;' }}">
                <div class="stage-panel-head">
                    <div>
                        <div class="stage-title" style="font-size:15px;">{{ $stage['title'] }}</div>
                        @if(!empty($stage['duration']))<div class="stage-sub">{{ $stage['duration'] }}</div>@endif
                    </div>
                    @if($panelState === 'done')<span class="status paid">Completed</span>
                    @elseif($panelState === 'current')<span class="status due">In Progress</span>
                    @else<span class="status status-locked">Upcoming</span>@endif
                </div>

                @if($panelState === 'current')
                    <div class="stage-flow-hint">Check off skills as you learn them - that's just for your own tracking. To unlock the next stage, submit a project below; your reviewer's approval is what advances you.</div>
                @elseif($panelState === 'upcoming')
                    <div class="stage-flow-hint">Unlocks once your reviewer approves your current stage's project.</div>
                @endif

                @foreach($weeklyPlans[$i] ?? [] as $weekLabel => $weekSkills)
                    <div class="week-group">
                        <div class="week-label">{{ $weekLabel }}</div>
                        @foreach($weekSkills as $skillIndex => $skillText)
                            <label class="skill {{ $panelState !== 'current' ? 'skill-locked' : '' }}">
                                @if($panelState === 'current')
                                    <input type="checkbox" class="skill-toggle" data-key="skill-{{ $skillIndex }}" @checked(data_get($stageSkillProgress, "skill-{$skillIndex}"))>
                                @else
                                    <input type="checkbox" disabled @checked($panelState === 'done' && data_get($stageSkillProgress, "skill-{$skillIndex}"))>
                                @endif
                                {{ $skillText }}
                            </label>
                        @endforeach
                    </div>
                @endforeach

                @if(!empty($stage['projects']))
                    <div class="stage-projects">
                        <strong style="font-size:12.5px; color:var(--ink-soft);">Project(s) for this stage</strong>
                        <ul>
                            @foreach($stage['projects'] as $pi => $project)
                                @php
                                    // Backward-compatible: older/unmigrated curriculum
                                    // still has a plain string here instead of
                                    // {title, steps}. Show it as a plain line with
                                    // no "View Steps" if so, instead of breaking.
                                    $isDetailed = is_array($project);
                                    $projectTitle = $isDetailed ? ($project['title'] ?? 'Project') : $project;
                                @endphp
                                <li>
                                    {{ $projectTitle }}
                                    @if($isDetailed && !empty($project['steps']))
                                        <button type="button" class="project-steps-link" onclick="openModal('projectModal-{{ $i }}-{{ $pi }}')">View Steps</button>
                                    @endif
                                </li>
                                @if($isDetailed && !empty($project['steps']))
                                    <div class="modal-overlay" id="projectModal-{{ $i }}-{{ $pi }}">
                                        <div class="modal-box">
                                            <h3>{{ $projectTitle }}</h3>
                                            <div class="hint">Step by step - what you need to do to complete this project.</div>
                                            <ol class="project-steps-list">
                                                @foreach($project['steps'] as $step)
                                                    <li>{{ $step }}</li>
                                                @endforeach
                                            </ol>
                                            <div class="modal-actions">
                                                <button class="btn" type="button" onclick="closeModal('projectModal-{{ $i }}-{{ $pi }}')">Got it</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($panelState === 'current')
                    <form method="POST" action="{{ route('academy.dashboard.submit') }}" class="stage-submit-form">
                        @csrf
                        <strong style="font-size:12.5px; color:var(--ink-soft);">Submit a Project for This Stage</strong>
                        <input type="text" name="project_title" placeholder="Project title" required style="margin-top:8px;">
                        <input type="url" name="submission_link" placeholder="GitHub / live demo link" required>
                        <textarea name="notes" placeholder="Notes for your reviewer (optional)" rows="2"></textarea>
                        <button class="btn" type="submit">Submit for Review</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    <div class="row2">
        <div class="card">
            <strong>Fees</strong>
            @if($unpaidInvoices->isNotEmpty())
                <div style="margin-top:8px; font-size:13px; color:var(--muted);">Total outstanding: <strong style="color:var(--ink);">Rs. {{ number_format($unpaidInvoices->sum('total_amount'), 2) }}</strong></div>
                <div style="margin-top:10px;">
                    @foreach($unpaidInvoices as $unpaid)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:7px 0; border-bottom:1px solid var(--line);">
                            <span style="font-size:13px;">{{ $unpaid->month->format('F Y') }} &middot; Rs. {{ number_format($unpaid->total_amount, 2) }}</span>
                            <span class="status due">{{ ucfirst($unpaid->status) }}</span>
                        </div>
                    @endforeach
                </div>
                @if($invoice->payment_proof_submitted_at)
                    <div style="margin-top:10px; font-size:12.5px; color:var(--muted);">Proof submitted for {{ $invoice->month->format('F Y') }} {{ $invoice->payment_proof_submitted_at->diffForHumans() }} - awaiting confirmation.</div>
                @else
                    <button class="btn btn-outline" type="button" style="margin-top:10px;" onclick="openModal('paymentProofModal')">Upload Payment Proof ({{ $invoice->month->format('M Y') }})</button>
                @endif
            @else
                <div style="margin-top:8px; color:var(--muted);">All fees paid - nothing outstanding.</div>
            @endif
        </div>
        <div class="card">
            <strong>Today's Attendance</strong>
            <div style="margin-top:10px; display:flex; gap:10px;">
                <button class="btn" type="button" onclick="openModal('checkinModal')">Check In</button>
                <button class="btn btn-outline" type="button" onclick="openModal('checkoutModal')">Check Out</button>
            </div>
        </div>
    </div>

    <!-- Check-in dialog: asks for today's plan (with an example), and shows
         yesterday's plan for reference - same idea as the main portal, but
         without any Clockify integration (Academy students don't log time
         in Clockify). -->
    <div class="modal-overlay" id="checkinModal">
        <div class="modal-box">
            <h3>Check In</h3>
            <div class="hint">Let your instructor know what you're working on today.</div>
            <form method="POST" action="{{ route('checkin.store') }}">
                @csrf
                <label>Yesterday's Plan (for reference)</label>
                <textarea id="yesterdaysWorkPlan" rows="3" readonly style="background:var(--bg); color:var(--muted);"></textarea>

                <label for="today_work_plan">Today's Plan</label>
                <textarea id="today_work_plan" name="today_work_plan" rows="5" required
                    placeholder="Example: Finish Stage 2 exercises 3-5, watch the Pandas groupby video, and push today's practice code to GitHub."></textarea>

                <div class="modal-actions">
                    <button class="btn btn-outline" type="button" onclick="closeModal('checkinModal')">Cancel</button>
                    <button class="btn" type="submit">Check In</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Check-out dialog: simple end-of-day report + tomorrow's plan, both
         with examples. No auto-filled Clockify entries like the main
         portal's checkout - kept deliberately simple for the Academy. -->
    <div class="modal-overlay" id="checkoutModal">
        <div class="modal-box">
            <h3>Check Out</h3>
            <div class="hint">A quick summary of today, and what's next.</div>
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <label for="end_of_day_report">End of Day Report</label>
                <textarea id="end_of_day_report" name="end_of_day_report" rows="5" required
                    placeholder="Example: Completed exercises 3-5, learned how groupby and agg work together, pushed the code to GitHub. Got stuck merging two dataframes but solved it after re-reading the docs."></textarea>

                <label for="tomorrow_work_plan">Plan for Tomorrow</label>
                <textarea id="tomorrow_work_plan" name="tomorrow_work_plan" rows="3" required
                    placeholder="Example: Start the Stage 2 capstone dataset-cleaning project and read the Matplotlib docs before class."></textarea>

                <div class="modal-actions">
                    <button class="btn btn-outline" type="button" onclick="closeModal('checkoutModal')">Cancel</button>
                    <button class="btn" type="submit">Check Out</button>
                </div>
            </form>
        </div>
    </div>

    @if($invoice && $invoice->status !== 'paid' && !$invoice->payment_proof_submitted_at)
    <!-- Payment proof dialog: student attaches a screenshot (bank transfer,
         JazzCash, EasyPaisa, etc.) - the accountant still makes the final
         call, this just gives them something to check instead of chasing
         it down. -->
    <div class="modal-overlay" id="paymentProofModal">
        <div class="modal-box">
            <h3>Upload Payment Proof</h3>
            <div class="hint">Attach a screenshot of your {{ $invoice->month->format('F Y') }} fee payment (Rs. {{ number_format($invoice->total_amount, 2) }}). Your accountant will confirm it.</div>
            <form method="POST" action="{{ route('academy.dashboard.submit-payment-proof', $invoice) }}" enctype="multipart/form-data">
                @csrf
                <label for="proof">Payment Screenshot</label>
                <input type="file" id="proof" name="proof" accept="image/*" required>

                <div class="modal-actions">
                    <button class="btn btn-outline" type="button" onclick="closeModal('paymentProofModal')">Cancel</button>
                    <button class="btn" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($certificates->isNotEmpty())
    <div class="card">
        <strong>My Certificates</strong>
        <div style="margin-top:10px;">
            @foreach($certificates as $cert)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid var(--line);">
                    <div>
                        <div style="font-weight:700; color:var(--ink);">{{ $cert->title }}</div>
                        <div style="font-size:12px; color:var(--muted);">{{ ucfirst($cert->type) }} certificate &middot; Issued {{ $cert->issued_at?->format('d M Y') }}</div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        @if($cert->pdf_path)
                            <a href="{{ asset('storage/' . $cert->pdf_path) }}" download class="btn" style="padding:8px 14px; font-size:12px;">Download</a>
                        @endif
                        <a href="{{ route('academy.certificate.verify', $cert->verify_code) }}" target="_blank" class="btn btn-outline" style="padding:8px 14px; font-size:12px;">Verify</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($reviews->isNotEmpty())
    <div class="card">
        <strong>Recent Submissions</strong>
        @foreach($reviews as $review)
            <div style="padding:12px 0; border-bottom:1px solid var(--line);">
                <div style="font-weight:700; color:var(--ink);">{{ $review->project_title }}
                    <span class="status {{ $review->reviewer_status === 'approved' ? 'paid' : 'due' }}">{{ ucfirst(str_replace('_', ' ', $review->reviewer_status)) }}</span>
                </div>
                @if($review->ai_feedback)
                    <div style="font-size:12.5px; color:var(--muted); margin-top:4px;">AI: {{ $review->ai_feedback }}</div>
                @endif
            </div>
        @endforeach
    </div>
    @endif
</div>

<script>
document.querySelectorAll('.skill-toggle').forEach(function (el) {
    el.addEventListener('change', function () {
        fetch('{{ route('academy.dashboard.toggle-skill') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ skill_key: el.dataset.key })
        });
    });
});

function openModal(id) {
    document.getElementById(id).classList.add('open');
    if (id === 'checkinModal') {
        fetch('{{ route('get.yesterdays.plan') }}')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                document.getElementById('yesterdaysWorkPlan').value = data.yesterdaysWorkPlan || 'No plan recorded for yesterday.';
            })
            .catch(function () {
                document.getElementById('yesterdaysWorkPlan').value = 'Could not load yesterday\'s plan.';
            });
    }
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

function showStage(index) {
    document.querySelectorAll('.stage-panel').forEach(function (el) { el.style.display = 'none'; });
    document.querySelectorAll('.stage-tab').forEach(function (el) { el.classList.remove('active'); });
    document.getElementById('stage-panel-' + index).style.display = '';
    document.getElementById('tab-btn-' + index).classList.add('active');
}
</script>
</body>
</html>
