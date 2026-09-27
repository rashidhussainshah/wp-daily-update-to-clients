<!doctype html>
<html>
<head><meta charset="utf-8"><title>{{ $enrollment->user->name }} - Progress</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --muted:#64748b; --line:#e2e8f0; --g-50:#f0fdf4; --g-100:#dcfce7; --g-600:#16a34a; --g-700:#15803d; --g-500:#22c55e; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#f8faf9; }
    .hero{ background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; padding:40px 32px; }
    .wrap{ max-width:820px; margin:0 auto; padding:0 24px 60px; }
    .card{ background:#fff; border:1px solid var(--line); border-radius:14px; padding:18px 20px; margin-bottom:14px; }
    .day{ padding-left:20px; border-left:2px solid var(--line); margin-bottom:14px; position:relative; }
    .day::before{ content:''; position:absolute; left:-6px; top:2px; width:12px; height:12px; border-radius:50%; background:var(--g-600); }
</style>
</head>
<body>
<div class="hero">
    <div style="max-width:820px; margin:0 auto;">
        <span style="background:rgba(255,255,255,0.16); padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700;">Parent View &middot; Read Only</span>
        <h1 style="margin:14px 0 4px;">{{ $enrollment->user->name }}</h1>
        <div>{{ $enrollment->track->name }} &middot; Stage {{ $enrollment->current_stage + 1 }} of {{ count($enrollment->track->stages()) }} ({{ $enrollment->progressPercent() }}%)</div>
    </div>
</div>

<div class="wrap">
    <div class="card" style="margin-top:-24px;">
        <strong>Certifications Earned</strong>
        @forelse($certificates as $cert)
            <div style="margin-top:8px;">{{ $cert->title }} &middot; {{ $cert->issued_at->format('M j, Y') }}</div>
        @empty
            <div style="margin-top:8px; color:var(--muted);">None yet.</div>
        @endforelse
    </div>

    <h2 style="color:var(--ink); font-size:17px;">Daily Activity</h2>
    @forelse($checkins as $checkin)
        <div class="day card">
            <strong>{{ $checkin->checkin_at?->format('l, M j') }}</strong>
            <div style="font-size:12px; color:var(--muted);">
                Checked in {{ $checkin->checkin_at?->format('g:i A') }}
                @if($checkin->checkout_at) &middot; Checked out {{ $checkin->checkout_at->format('g:i A') }} @endif
            </div>
            @if($checkin->today_work_plan)
                <div style="margin-top:8px; font-size:13px;"><strong style="color:var(--g-700); font-size:11px; text-transform:uppercase;">Today's Plan</strong><br>{{ $checkin->today_work_plan }}</div>
            @endif
            @if($checkin->end_of_day_report)
                <div style="margin-top:8px; font-size:13px;"><strong style="color:var(--muted); font-size:11px; text-transform:uppercase;">End of Day</strong><br>{{ $checkin->end_of_day_report }}</div>
            @endif
        </div>
    @empty
        <p style="color:var(--muted);">No activity recorded yet.</p>
    @endforelse

    @if($showFee)
        <h2 style="color:var(--ink); font-size:17px;">Fee Status</h2>
        <div class="card">
            @if($invoice)
                {{ $invoice->month->format('F Y') }}: Rs. {{ number_format($invoice->total_amount, 2) }} &middot; {{ ucfirst($invoice->status) }}
            @else
                No invoice yet.
            @endif
        </div>
    @endif

    <p style="text-align:center; color:#94a3b8; font-size:12px; margin-top:30px;">This is a private, read-only link shared by Webpenter IT Academy.</p>
</div>
</body>
</html>
