<!doctype html>
<html>
<head><meta charset="utf-8"><title>Certificate Verification</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
<style>
    body{ font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:linear-gradient(180deg,#f0fdf4,#fff); margin:0; display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .card{ background:#fff; border:1px solid #e2e8f0; border-radius:20px; padding:40px; max-width:440px; text-align:center; box-shadow:0 12px 40px rgba(15,23,42,0.08); }
    .icon{ width:70px; height:70px; border-radius:50%; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; }
    .row{ display:flex; justify-content:space-between; text-align:left; font-size:13.5px; padding:8px 0; border-top:1px solid #f1f5f9; }
</style>
</head>
<body>
<div class="card">
    @if($certificate)
        <div class="icon" style="background:linear-gradient(135deg,#15803d,#22c55e);">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h1 style="font-size:22px;">Certificate Verified</h1>
        <p style="color:#64748b; font-size:13px;">This certificate matches our official Academy records.</p>
        <div class="row"><span>Recipient</span><strong>{{ $certificate->recipient_name }}</strong></div>
        <div class="row"><span>{{ $certificate->type === 'track' ? 'Track' : 'Course' }}</span><strong>{{ $certificate->title }}</strong></div>
        <div class="row"><span>Issued on</span><strong>{{ $certificate->issued_at->format('F j, Y') }}</strong></div>
        <div class="row"><span>Certificate ID</span><strong>{{ $certificate->verify_code }}</strong></div>
    @else
        <div class="icon" style="background:#fef2f2;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/></svg>
        </div>
        <h1 style="font-size:22px;">Not Found</h1>
        <p style="color:#64748b; font-size:13px;">This certificate ID doesn't match any record in our system.</p>
    @endif
</div>
</body>
</html>
