<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Letter Templates</title>
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
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .tpl-item{ display:flex; align-items:center; justify-content:space-between; gap:14px; padding:16px 0; border-bottom:1px solid var(--line); flex-wrap:wrap; }
    .tpl-item:last-child{ border:0; }
    .tpl-name{ font-weight:700; color:var(--ink); font-size:14.5px; }
    .tpl-meta{ color:var(--muted); font-size:12px; margin-top:3px; }
    .pill{ display:inline-block; padding:2px 9px; border-radius:999px; font-size:10.5px; font-weight:700; margin-right:5px; }
    .pill-cat{ background:var(--g-50); color:var(--g-700); border:1px solid var(--g-100); }
    .pill-design{ background:var(--bg); color:var(--ink-soft); border:1px solid var(--line); }
    .pill-active{ background:var(--g-100); color:var(--g-700); }
    .pill-draft{ background:#fef3c7; color:#b45309; }
    .actions{ display:flex; gap:8px; flex-wrap:wrap; }
    .btn{ display:inline-block; padding:11px 20px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); padding:8px 14px; border-radius:9px; font-weight:700; font-size:12.5px; cursor:pointer; font-family:inherit; text-decoration:none; display:inline-block; }
    .btn-danger-outline{ background:none; border:1.5px solid #fecaca; color:#b91c1c; padding:8px 14px; border-radius:9px; font-weight:700; font-size:12.5px; cursor:pointer; font-family:inherit; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .tpl-item{ flex-direction:column; align-items:flex-start; } .actions{ width:100%; } }
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
        <h1>Letter Templates</h1>
        <div class="sub">The custom builder - create a new letter type any time, no code changes needed.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('hr-documents.dashboard') }}">&larr; Dashboard</a>
        <a href="{{ route('hr-documents.templates.create') }}" class="primary">+ New Template</a>
        <a href="{{ route('hr-documents.issue.create') }}">Issue a Letter</a>
        <a href="{{ route('hr-documents.issuances.index') }}">History</a>
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif

    <div class="card">
        @forelse($templates as $template)
            <div class="tpl-item">
                <div>
                    <div class="tpl-name">{{ $template->name }}</div>
                    <div class="tpl-meta">
                        <span class="pill pill-cat">{{ $template->category }}</span>
                        <span class="pill pill-design">{{ $template->design }}</span>
                        <span class="pill {{ $template->is_active ? 'pill-active' : 'pill-draft' }}">{{ $template->is_active ? 'Active' : 'Draft' }}</span>
                        &middot; issued {{ $template->issuances_count }} time{{ $template->issuances_count === 1 ? '' : 's' }}
                    </div>
                </div>
                <div class="actions">
                    <a href="{{ route('hr-documents.templates.edit', $template) }}" class="btn-outline">Edit</a>
                    <form method="POST" action="{{ route('hr-documents.templates.destroy', $template) }}" onsubmit="return confirm('Delete this template? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-outline">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No templates yet - <a href="{{ route('hr-documents.templates.create') }}">create your first one</a>.</div>
        @endforelse
    </div>
</div>
</body>
</html>
