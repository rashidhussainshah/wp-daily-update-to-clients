<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Accountant Dashboard</title>
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
    .stats{ display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px; }
    .stat{ background:var(--white); border:1px solid var(--line); border-radius:14px; padding:16px 18px; }
    .stat .num{ font-size:24px; font-weight:800; color:var(--ink); }
    .stat .lbl{ font-size:11.5px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .queue-item{ display:flex; align-items:center; gap:16px; padding:16px 0; border-bottom:1px solid var(--line); }
    .queue-item:last-child{ border:0; }
    .photo{ width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid var(--g-100); flex-shrink:0; }
    .info{ flex:1; min-width:0; }
    .info .name{ font-weight:700; color:var(--ink); font-size:14.5px; }
    .info .meta{ color:var(--muted); font-size:12.5px; margin-top:2px; }
    .proof-thumb{ width:52px; height:52px; border-radius:8px; object-fit:cover; border:1px solid var(--line); flex-shrink:0; }
    .btn{ display:inline-block; padding:9px 16px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13px; cursor:pointer; text-decoration:none; white-space:nowrap; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    @media (max-width:640px){
        .wrap{ padding:24px 16px; }
        .stats{ grid-template-columns:1fr; }
        .queue-item{ flex-wrap:wrap; }
    }
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
        <h1>Accountant Dashboard</h1>
        <div class="sub">What needs a decision right now - not every invoice at once.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('academy.accountant.index') }}" class="active">Needs Review</a>
        <a href="{{ route('academy.accountant.students') }}">Manage Students</a>
        <a href="{{ route('academy.help') }}">❓ Help</a>
        <a href="{{ route('academy.fees.index') }}">Open Full Voyager View &rarr;</a>
        @if(Auth::user()->hasPermission('browse_admin'))
            <a href="{{ route('voyager.dashboard') }}">Go to Voyager Admin (Leaves &amp; More) &rarr;</a>
        @endif
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $needsReview->count() }}</div><div class="lbl">Needs Review</div></div>
        <div class="stat"><div class="num">{{ $pendingNoProof }}</div><div class="lbl">Pending, No Proof Yet</div></div>
        <div class="stat"><div class="num">Rs. {{ number_format($collectedThisMonth) }}</div><div class="lbl">Collected This Month</div></div>
    </div>

    <div class="card">
        <strong>Payment Proofs Awaiting Confirmation</strong>
        <div style="margin-top:6px;">
            @forelse($needsReview as $invoice)
                <div class="queue-item">
                    <img class="photo" src="{{ $invoice->enrollment->user->avatar ? asset('storage/' . $invoice->enrollment->user->avatar) : asset('storage/users/default.png') }}" alt="">
                    <div class="info">
                        <div class="name">{{ $invoice->enrollment->user->name }}</div>
                        <div class="meta">{{ $invoice->enrollment->track->name }} &middot; {{ $invoice->month->format('F Y') }} &middot; Rs. {{ number_format($invoice->total_amount, 2) }} &middot; submitted {{ $invoice->payment_proof_submitted_at->diffForHumans() }}</div>
                    </div>
                    <a href="{{ asset('storage/' . $invoice->payment_proof_path) }}" target="_blank">
                        <img class="proof-thumb" src="{{ asset('storage/' . $invoice->payment_proof_path) }}" alt="Proof">
                    </a>
                    <form method="POST" action="{{ route('academy.accountant.mark-paid', $invoice) }}">
                        @csrf
                        <button class="btn" type="submit">Mark Paid</button>
                    </form>
                </div>
            @empty
                <div class="empty">Nothing waiting on you right now.</div>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
