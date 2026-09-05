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
    .skill input{ width:18px; height:18px; }
    .btn{ display:inline-block; padding:11px 20px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); }
    input,textarea{ width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; margin-bottom:12px; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
</style>
</head>
<body>
<div class="wrap">
    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif

    <div class="card">
        <h1>My Learning Journey</h1>
        <div class="sub">{{ Auth::user()->name }} &middot; {{ $enrollment->track->name }} &middot; Stage {{ $enrollment->current_stage + 1 }} of {{ count($stages) }} &middot; {{ $enrollment->progressPercent() }}% complete</div>
    </div>

    <div class="row2">
        <div class="card">
            <strong>{{ now()->format('F') }} Fee</strong>
            @if($invoice)
                <div style="margin-top:8px;">Rs. {{ number_format($invoice->total_amount, 2) }}
                    <span class="status {{ $invoice->status === 'paid' ? 'paid' : 'due' }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            @else
                <div style="margin-top:8px; color:var(--muted);">No invoice yet this month.</div>
            @endif
        </div>
        <div class="card">
            <strong>Today's Attendance</strong>
            <div style="margin-top:10px; display:flex; gap:10px;">
                <form method="POST" action="{{ route('checkin.store') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class="btn" type="submit">Check In</button></form>
                <form method="POST" action="{{ route('checkout.store') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class="btn btn-outline" type="submit">Check Out</button></form>
            </div>
        </div>
    </div>

    @if($currentStage)
    <div class="row2">
        <div class="card">
            <strong>Skills Checklist &middot; {{ $currentStage['title'] }}</strong>
            <div style="margin-top:14px;">
                @foreach($currentStage['skills'] as $i => $skill)
                    <label class="skill">
                        <input type="checkbox" class="skill-toggle" data-key="skill-{{ $i }}" @checked(data_get($progress, "skills.skill-{$i}"))>
                        {{ $skill }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="card">
            <strong>Submit a Project</strong>
            <form method="POST" action="{{ route('academy.dashboard.submit') }}" style="margin-top:14px;">
                @csrf
                <input type="text" name="project_title" placeholder="Project title" required>
                <input type="url" name="submission_link" placeholder="GitHub / live demo link" required>
                <textarea name="notes" placeholder="Notes for your reviewer (optional)" rows="3"></textarea>
                <button class="btn" type="submit">Submit for Review</button>
            </form>
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
</script>
</body>
</html>
