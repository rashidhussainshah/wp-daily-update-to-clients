<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registered</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --muted:#64748b; --line:#e2e8f0; --g-50:#f0fdf4; --g-100:#dcfce7; --g-600:#16a34a; --g-700:#15803d; --g-500:#22c55e; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#f8faf9; }
    .wrap{ max-width:520px; margin:0 auto; padding:56px 24px; text-align:center; }
    .check{ width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,var(--g-700),var(--g-500)); display:flex; align-items:center; justify-content:center; margin:0 auto 20px; }
    h1{ font-size:22px; font-weight:800; color:var(--ink); }
    .card{ text-align:left; background:#fff; border:1px solid var(--line); border-radius:14px; padding:20px; margin-top:24px; }
    .row{ display:flex; justify-content:space-between; font-size:13.5px; padding:6px 0; color:#334155; }
    .total{ border-top:1px solid var(--g-100); margin-top:6px; padding-top:10px; font-weight:800; color:var(--ink); }
</style>
</head>
<body>
<div class="wrap">
    <div class="check"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    <h1>Welcome to {{ $enrollment->track->name }}!</h1>
    <p style="color:#64748b;">You're enrolled with <strong>{{ $enrollment->instructor->name ?? 'an instructor to be assigned' }}</strong> as your instructor.</p>

    @if($invoice)
        <div class="card">
            <div class="row"><span>Registration fee</span><span>Rs. {{ number_format($invoice->registration_fee_amount, 2) }}</span></div>
            <div class="row"><span>First month's fee</span><span>Rs. {{ number_format($invoice->monthly_fee_amount, 2) }}</span></div>
            <div class="row total"><span>Total due</span><span>Rs. {{ number_format($invoice->total_amount, 2) }}</span></div>
        </div>
    @endif

    <p style="color:#94a3b8; font-size:12.5px; margin-top:24px;">Check your email for login instructions, or contact the academy team.</p>
</div>
</body>
</html>
