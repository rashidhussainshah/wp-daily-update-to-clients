<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HR Documents</title>
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
    .nav-tabs a.primary{ background:var(--g-600); border-color:var(--g-600); color:#fff; }
    .stats{ display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px; }
    .stat{ background:var(--white); border:1px solid var(--line); border-radius:14px; padding:16px 18px; }
    .stat .num{ font-size:24px; font-weight:800; color:var(--ink); }
    .stat .lbl{ font-size:11.5px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
    .issue-item{ display:flex; align-items:center; justify-content:space-between; gap:14px; padding:14px 0; border-bottom:1px solid var(--line); flex-wrap:wrap; }
    .issue-item:last-child{ border:0; }
    .issue-item .name{ font-weight:700; color:var(--ink); font-size:14px; }
    .issue-item .meta{ color:var(--muted); font-size:12px; margin-top:2px; }
    .manual-badge{ font-size:10px; background:var(--bg); border:1px solid var(--line); color:var(--muted); padding:2px 8px; border-radius:999px; margin-left:6px; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .stats{ grid-template-columns:1fr; } }
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

    <div class="card">
        <h1>HR Documents</h1>
        <div class="sub">Build letter templates once, issue experience/relieving/internship letters to anyone in seconds.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('hr-documents.issue.create') }}" class="primary">+ Issue a Letter</a>
        <a href="{{ route('hr-documents.templates.index') }}">Templates</a>
        <a href="{{ route('hr-documents.issuances.index') }}">History</a>
        <a href="{{ route('academy.help') }}">❓ Help</a>
        @if(Auth::user()->hasPermission('browse_admin'))
            <a href="{{ route('voyager.dashboard') }}">Go to Voyager Admin &rarr;</a>
        @endif
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $activeTemplates }}</div><div class="lbl">Active Templates</div></div>
        <div class="stat"><div class="num">{{ $issuedThisMonth }}</div><div class="lbl">Issued This Month</div></div>
        <div class="stat"><div class="num">{{ $issuedTotal }}</div><div class="lbl">Issued All Time</div></div>
    </div>

    <div class="card">
        <strong>Recently Issued</strong>
        <div style="margin-top:6px;">
            @forelse($recent as $issuance)
                <div class="issue-item">
                    <div>
                        <div class="name">{{ $issuance->recipient_name }}@if(!$issuance->user_id)<span class="manual-badge">manual entry</span>@endif</div>
                        <div class="meta">{{ $issuance->template->name }} &middot; issued by {{ $issuance->issuedBy?->name ?? '-' }} &middot; {{ $issuance->issued_at?->format('d M Y, g:ia') }}</div>
                    </div>
                </div>
            @empty
                <div class="empty">No letters issued yet - <a href="{{ route('hr-documents.issue.create') }}">issue your first one</a>.</div>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
