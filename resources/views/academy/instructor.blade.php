<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Students</title>
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
    .stats{ display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px; }
    .stat{ background:var(--white); border:1px solid var(--line); border-radius:14px; padding:16px 18px; }
    .stat .num{ font-size:24px; font-weight:800; color:var(--ink); }
    .stat .lbl{ font-size:11.5px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
    .student-item{ display:flex; align-items:flex-start; gap:16px; padding:18px 0; border-bottom:1px solid var(--line); }
    .student-item:last-child{ border:0; }
    .photo{ width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid var(--g-100); flex-shrink:0; }
    .info{ flex:1; min-width:0; }
    .info .name{ font-weight:700; color:var(--ink); font-size:15px; }
    .info .meta{ color:var(--muted); font-size:12.5px; margin-top:2px; }
    .level-badge{ display:inline-flex; align-items:center; gap:5px; padding:3px 11px; border-radius:999px; background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); font-weight:700; font-size:11px; margin-top:6px; }
    .fee-history{ margin-top:10px; display:flex; flex-wrap:wrap; gap:8px; }
    .fee-pill{ font-size:11px; padding:4px 10px; border-radius:999px; font-weight:600; }
    .fee-paid{ background:var(--g-100); color:var(--g-700); }
    .fee-due{ background:#fef3c7; color:#b45309; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .stats{ grid-template-columns:1fr; } .student-item{ flex-wrap:wrap; } }
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
        <h1>My Students</h1>
        <div class="sub">{{ Auth::user()->name }} &middot; everything you're paid on, in one place.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('academy.help') }}">❓ Help</a>
        @if(Auth::user()->hasPermission('browse_admin'))
            <a href="{{ route('voyager.dashboard') }}">Go to Voyager Admin (Leaves &amp; More) &rarr;</a>
        @endif
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $enrollments->count() }}</div><div class="lbl">Students</div></div>
        <div class="stat"><div class="num">Rs. {{ number_format($commissionThisMonth) }}</div><div class="lbl">Commission This Month</div></div>
        <div class="stat"><div class="num">Rs. {{ number_format($totalCommission) }}</div><div class="lbl">Total Commission Earned</div></div>
    </div>

    <div class="card">
        <strong>Student Details</strong>
        <div style="margin-top:6px;">
            @forelse($enrollments as $enrollment)
                @php $level = $enrollment->levelTier(); @endphp
                <div class="student-item">
                    <img class="photo" src="{{ $enrollment->user->avatar ? asset('storage/' . $enrollment->user->avatar) : asset('storage/users/default.png') }}" alt="">
                    <div class="info">
                        <div class="name">{{ $enrollment->user->name }}</div>
                        <div class="meta">{{ $enrollment->track->name }} &middot; Stage {{ $enrollment->current_stage + 1 }} &middot; {{ $enrollment->progressPercent() }}% complete</div>
                        <div class="level-badge">{{ $level['icon'] }} {{ $level['label'] }}</div>
                        <div class="fee-history">
                            @forelse($enrollment->feeInvoices->sortByDesc('month') as $invoice)
                                <span class="fee-pill {{ $invoice->status === 'paid' ? 'fee-paid' : 'fee-due' }}">
                                    {{ $invoice->month->format('M Y') }}: {{ ucfirst($invoice->status) }}
                                    @if($invoice->instructor_commission_credited) &middot; Rs. {{ number_format($invoice->instructor_commission_amount, 2) }} @endif
                                </span>
                            @empty
                                <span class="meta">No invoices yet.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty">No students assigned to you yet.</div>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
