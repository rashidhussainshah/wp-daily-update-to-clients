<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Issuance History</title>
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
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .nav-tabs a{ padding:9px 16px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:13px; font-weight:600; background:var(--white); }
    .nav-tabs a.primary{ background:var(--g-600); border-color:var(--g-600); color:#fff; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .status-error{ background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .filter-row{ margin-bottom:16px; display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    select{ padding:9px 12px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px; background:var(--white); width:auto; }
    .row-item{ padding:16px 0; border-bottom:1px solid var(--line); }
    .row-item:last-child{ border:0; }
    .row-head{ display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .name{ font-weight:700; color:var(--ink); font-size:14.5px; }
    .manual-badge{ font-size:10px; background:var(--bg); border:1px solid var(--line); color:var(--muted); padding:2px 8px; border-radius:999px; margin-left:6px; }
    .meta{ color:var(--muted); font-size:12px; margin-top:3px; }
    .code{ font-family:ui-monospace,Menlo,monospace; font-size:11px; color:var(--muted); }
    .actions{ display:flex; gap:8px; flex-wrap:wrap; margin-top:10px; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); padding:6px 12px; border-radius:8px; font-weight:700; font-size:11.5px; cursor:pointer; font-family:inherit; text-decoration:none; display:inline-block; }
    .btn-mini{ background:none; border:0; color:var(--g-700); font-weight:700; font-size:11.5px; cursor:pointer; font-family:inherit; padding:6px 4px; text-decoration:underline; }
    .email-row{ display:none; margin-top:10px; gap:8px; flex-wrap:wrap; }
    .email-row.open{ display:flex; }
    .email-row input{ padding:8px 10px; border:1.5px solid var(--line); border-radius:8px; font-family:inherit; font-size:12.5px; max-width:240px; }
    .btn-send{ background:var(--g-600); color:#fff; border:0; padding:8px 14px; border-radius:8px; font-weight:700; font-size:12px; cursor:pointer; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    .pagination-wrap{ margin-top:16px; }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } }
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
        <h1>Issuance History</h1>
        <div class="sub">Who got what, when.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('hr-documents.dashboard') }}">&larr; Dashboard</a>
        <a href="{{ route('hr-documents.issue.create') }}" class="primary">+ Issue a Letter</a>
        <a href="{{ route('hr-documents.templates.index') }}">Templates</a>
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="status-error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <form method="GET" action="{{ route('hr-documents.issuances.index') }}" class="filter-row">
            <select name="user_id" onchange="this.form.submit()">
                <option value="">All employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected($filterUserId === $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>
            @if($filterUserId)
                <a href="{{ route('hr-documents.issuances.index') }}" class="btn-outline">Reset</a>
            @endif
        </form>

        @forelse($issuances as $issuance)
            <div class="row-item">
                <div class="row-head">
                    <div>
                        <div class="name">{{ $issuance->recipient_name }}@if(!$issuance->user_id)<span class="manual-badge">manual entry</span>@endif</div>
                        <div class="meta">{{ $issuance->template->name }} &middot; issued by {{ $issuance->issuedBy?->name ?? '-' }} &middot; {{ $issuance->issued_at?->format('d M Y, g:ia') }} &middot; <span class="code">{{ $issuance->verify_code }}</span></div>
                    </div>
                </div>
                <div class="actions">
                    @if($issuance->pdf_path)
                        <a href="{{ asset('storage/'.$issuance->pdf_path) }}" target="_blank" class="btn-outline">Preview</a>
                        <a href="{{ asset('storage/'.$issuance->pdf_path) }}" download class="btn-outline">Download</a>
                    @endif
                    <a href="{{ route('hr-documents.verify', $issuance->verify_code) }}" target="_blank" class="btn-mini">Verify page</a>
                    <button type="button" class="btn-mini email-toggle" data-target="email-{{ $issuance->id }}">Email</button>
                </div>
                <div class="email-row" id="email-{{ $issuance->id }}">
                    <form method="POST" action="{{ route('hr-documents.issuances.send-email', $issuance) }}" style="display:flex; gap:8px; flex-wrap:wrap;">
                        @csrf
                        <input type="email" name="email" value="{{ $issuance->recipient_email }}" placeholder="Send to email" required>
                        <button type="submit" class="btn-send">Send</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No letters issued yet - <a href="{{ route('hr-documents.issue.create') }}">issue your first one</a>.</div>
        @endforelse

        <div class="pagination-wrap">{{ $issuances->links() }}</div>
    </div>
</div>

<script>
document.querySelectorAll('.email-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById(btn.dataset.target).classList.toggle('open');
    });
});
</script>
</body>
</html>
