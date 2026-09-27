<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Students</title>
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
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .row-item{ display:flex; align-items:center; gap:16px; padding:14px 0; border-bottom:1px solid var(--line); }
    .row-item:last-child{ border:0; }
    .photo{ width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid var(--g-100); flex-shrink:0; }
    .info{ flex:1; min-width:0; }
    .info .name{ font-weight:700; color:var(--ink); font-size:14px; }
    .info .meta{ color:var(--muted); font-size:12.5px; margin-top:2px; }
    .btn-danger{ display:inline-block; padding:8px 14px; border:1.5px solid #fecaca; border-radius:10px; background:#fef2f2; color:#dc2626; font-weight:700; font-size:12.5px; cursor:pointer; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    .hint{ color:var(--muted); font-size:12.5px; margin-top:-2px; margin-bottom:14px; }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .row-item{ flex-wrap:wrap; } }
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
        <h1>Manage Students</h1>
        <div class="sub">Remove a student who dropped out or was added by mistake.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('academy.accountant.index') }}">Needs Review</a>
        <a href="{{ route('academy.accountant.students') }}" class="active">Manage Students</a>
        <a href="{{ route('academy.fees.index') }}">Open Full Voyager View &rarr;</a>
    </div>

    <div class="card">
        <div class="hint">Removing does not delete their account or history - it's reversible, and just stops their enrollment showing up in fees/reviews going forward.</div>
        @forelse($enrollments as $enrollment)
            <div class="row-item">
                <img class="photo" src="{{ $enrollment->user->avatar ? asset('storage/' . $enrollment->user->avatar) : asset('storage/users/default.png') }}" alt="">
                <div class="info">
                    <div class="name">{{ $enrollment->user->name }}</div>
                    <div class="meta">{{ $enrollment->track->name }} &middot; {{ $enrollment->user->email }}</div>
                </div>
                <form method="POST" action="{{ route('academy.accountant.remove-student', $enrollment) }}" onsubmit="return confirm('Remove {{ $enrollment->user->name }}\'s enrollment? This can be undone later if needed.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn-danger" type="submit">Remove</button>
                </form>
            </div>
        @empty
            <div class="empty">No active students.</div>
        @endforelse
    </div>
</div>
</body>
</html>
