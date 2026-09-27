<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Review Queue</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --line:#e2e8f0; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); }
    .wrap{ max-width:900px; margin:0 auto; padding:40px 24px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .nav-tabs a{ padding:9px 16px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:13px; font-weight:600; background:var(--white); }
    .nav-tabs a.active{ border-color:var(--g-600); background:var(--g-50); color:var(--g-700); }
    .stats{ display:grid; grid-template-columns:repeat(1, 1fr); gap:16px; margin-bottom:24px; max-width:260px; }
    .stat{ background:var(--white); border:1px solid var(--line); border-radius:14px; padding:16px 18px; }
    .stat .num{ font-size:24px; font-weight:800; color:var(--ink); }
    .stat .lbl{ font-size:11.5px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .queue-item{ padding:18px 0; border-bottom:1px solid var(--line); }
    .queue-item:last-child{ border:0; }
    .q-head{ display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .q-name{ font-weight:700; color:var(--ink); font-size:14.5px; }
    .q-meta{ color:var(--muted); font-size:12.5px; margin-top:2px; }
    .q-link{ font-size:12.5px; word-break:break-all; }
    .ai-box{ margin-top:12px; padding:12px 14px; border-radius:10px; background:var(--g-50); border:1px solid var(--g-100); font-size:13px; color:var(--ink-soft); }
    .ai-box.fallback{ background:#fffbeb; border-color:#fef3c7; color:#b45309; }
    .actions{ margin-top:14px; display:flex; gap:10px; }
    .btn{ display:inline-block; padding:9px 18px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13px; cursor:pointer; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .q-head{ flex-direction:column; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="top-bar">
        <form class="logout-form" method="POST" action="{{ route('voyager.logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif

    <div class="card">
        <h1>Review Queue</h1>
        <div class="sub">Submissions waiting on your decision - approving advances the student to their next stage.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('academy.reviewer.index') }}" class="active">Needs Review</a>
        <a href="{{ route('academy.help') }}">❓ Help</a>
        <a href="{{ route('academy.review.index') }}">Open Full Voyager View &rarr;</a>
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $reviews->count() }}</div><div class="lbl">Pending Review</div></div>
    </div>

    <div class="card">
        @forelse($reviews as $review)
            <div class="queue-item">
                <div class="q-head">
                    <div>
                        <div class="q-name">{{ $review->enrollment->user->name }} &middot; {{ $review->project_title }}</div>
                        <div class="q-meta">{{ $review->enrollment->track->name }} &middot; Stage {{ $review->stage_index + 1 }}</div>
                        <div class="q-link"><a href="{{ $review->submission_link }}" target="_blank">{{ $review->submission_link }}</a></div>
                    </div>
                </div>

                @if($review->ai_verdict)
                    <div class="ai-box">
                        <strong>AI Suggestion: {{ ucfirst(str_replace('_', ' ', $review->ai_verdict)) }}</strong>
                        @if($review->ai_score) &middot; {{ $review->ai_score }}/10 @endif
                        <div style="margin-top:4px;">{{ $review->ai_feedback }}</div>
                    </div>
                @elseif($review->ai_feedback)
                    <div class="ai-box fallback">{{ $review->ai_feedback }}</div>
                @endif

                <div class="actions">
                    <form method="POST" action="{{ route('academy.review.approve', $review) }}">
                        @csrf
                        <button type="submit" class="btn">Approve &amp; Award</button>
                    </form>
                    <form method="POST" action="{{ route('academy.review.send-back', $review) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline">Send Back</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">Nothing waiting on you right now.</div>
        @endforelse
    </div>
</div>
</body>
</html>
