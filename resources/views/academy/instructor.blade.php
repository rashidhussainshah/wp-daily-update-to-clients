<!doctype html>
<html>
<head><meta charset="utf-8"><title>My Students</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
<style>
    body{ font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#f8faf9; margin:0; }
    .wrap{ max-width:900px; margin:0 auto; padding:36px 24px; }
    .card{ background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:18px 22px; margin-bottom:14px; }
    h1{ color:#0f172a; }
</style>
</head>
<body>
<div class="wrap">
    <h1>My Students</h1>
    <p style="color:#64748b;">Total commission credited to date: <strong>Rs. {{ number_format($totalCommission, 2) }}</strong></p>

    @foreach($enrollments as $enrollment)
        <div class="card">
            <strong>{{ $enrollment->user->name }}</strong> &middot; {{ $enrollment->track->name }} &middot; Stage {{ $enrollment->current_stage + 1 }}
            <div style="font-size:12.5px; color:#64748b; margin-top:6px;">
                @foreach($enrollment->feeInvoices as $invoice)
                    {{ $invoice->month->format('M Y') }}: {{ ucfirst($invoice->status) }}@if($invoice->instructor_commission_credited) (Rs. {{ number_format($invoice->instructor_commission_amount, 2) }} credited)@endif &nbsp;
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
