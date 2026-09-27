<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Certificates</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --line:#e2e8f0; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); }
    .wrap{ max-width:1040px; margin:0 auto; padding:40px 24px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .nav-tabs a{ padding:9px 16px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:13px; font-weight:600; background:var(--white); }
    .gallery{ display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:18px; }
    .cert-card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:20px; position:relative; overflow:hidden; }
    .cert-card::before{ content:''; position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg,#15803d,#22c55e); }
    .cert-head{ display:flex; gap:12px; align-items:center; margin-top:6px; }
    .photo{ width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid var(--g-100); flex-shrink:0; }
    .cert-name{ font-weight:700; color:var(--ink); font-size:14.5px; }
    .cert-title{ color:var(--ink-soft); font-size:12.5px; margin-top:1px; }
    .type-badge{ display:inline-block; padding:3px 10px; border-radius:999px; font-size:10.5px; font-weight:700; margin-top:8px; }
    .type-track{ background:var(--g-100); color:var(--g-700); }
    .type-course{ background:#dbeafe; color:#1d4ed8; }
    .level-badge{ display:inline-block; padding:3px 10px; border-radius:999px; background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); font-weight:700; font-size:10.5px; margin-top:8px; margin-left:6px; }
    .meta-row{ font-size:11.5px; color:var(--muted); margin-top:10px; }
    .code{ font-family:monospace; font-size:11px; color:var(--muted); }
    .cert-actions{ display:flex; gap:8px; margin-top:14px; flex-wrap:wrap; }
    .btn{ display:inline-block; padding:8px 14px; border:0; border-radius:9px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:12px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); font-size:12px; padding:7px 13px; border-radius:9px; cursor:pointer; font-family:inherit; }
    .empty{ text-align:center; padding:36px 20px; color:var(--muted); }
    .pagination-wrap{ margin-top:20px; }
    .suggest-panel{ margin-top:14px; padding-top:14px; border-top:1px solid var(--line); display:none; }
    .suggest-field{ margin-bottom:10px; }
    .suggest-field .lbl{ font-size:10.5px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.03em; margin-bottom:3px; display:flex; justify-content:space-between; align-items:center; }
    .suggest-field .val{ font-size:12.5px; color:var(--ink-soft); background:var(--bg); border:1px solid var(--line); border-radius:8px; padding:8px 10px; }
    .suggest-field .val.is-loading{ color:var(--muted); font-style:italic; animation:val-pulse 1.1s ease-in-out infinite; }
    @keyframes val-pulse{ 0%,100%{ opacity:.5; } 50%{ opacity:1; } }
    .copy-mini{ background:none; border:0; color:var(--g-700); font-weight:700; font-size:10.5px; cursor:pointer; font-family:inherit; padding:0; }
    .suggest-note{ font-size:10.5px; color:var(--muted); margin-top:6px; }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .section-title{ font-size:15px; font-weight:800; color:var(--ink); margin:30px 0 4px; }
    .section-sub{ color:var(--muted); font-size:12.5px; margin-bottom:14px; }
    .posted-badge{ display:inline-block; padding:3px 10px; border-radius:999px; background:var(--g-100); color:var(--g-700); font-weight:700; font-size:10.5px; margin-top:8px; margin-left:6px; }
    .cert-card.is-posted{ opacity:.85; }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .gallery{ grid-template-columns:1fr; } }
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
        <h1>Certificates</h1>
        <div class="sub">Every certificate issued so far - download the PDF or copy ready-to-paste text for LinkedIn/social media.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('academy.help') }}">❓ Help</a>
        <a href="{{ route('academy.certificates.index') }}">Open Full Voyager View &rarr;</a>
        @if(Auth::user()->hasPermission('browse_admin'))
            <a href="{{ route('voyager.dashboard') }}">Go to Voyager Admin &rarr;</a>
        @endif
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('academy.marketing.index') }}" style="margin-bottom:18px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <select name="resource" onchange="this.form.submit()" style="padding:9px 13px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px;">
            <option value="">All resources</option>
            @foreach($resources as $resource)
                <option value="{{ $resource->id }}" @selected((string) $resourceId === (string) $resource->id)>{{ $resource->name }}</option>
            @endforeach
        </select>
        <select name="design" onchange="this.form.submit()" style="padding:9px 13px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px;">
            <option value="">All designs</option>
            @foreach($designs as $key => $label)
                <option value="{{ $key }}" @selected($design === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="sort" onchange="this.form.submit()" style="padding:9px 13px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px;">
            <option value="recent" @selected($sort === 'recent')>Most recent first</option>
            <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
        </select>
        @if($design || $resourceId || $sort !== 'recent')
            <a href="{{ route('academy.marketing.index') }}" class="btn-outline" style="text-decoration:none;">Reset</a>
        @endif
    </form>

    <div class="gallery">
        @forelse($certificates as $cert)
            @php
                $verifyUrl = route('academy.certificate.verify', $cert->verify_code);
                $pdfUrl = $cert->pdf_path ? asset('storage/' . $cert->pdf_path) : null;
                $level = $cert->type === 'track' && $cert->enrollment ? $cert->enrollment->levelTier() : null;
            @endphp
            <div class="cert-card">
                <div class="cert-head">
                    <img class="photo" src="{{ $cert->user->avatar ? asset('storage/' . $cert->user->avatar) : asset('storage/users/default.png') }}" alt="">
                    <div>
                        <div class="cert-name">{{ $cert->recipient_name }}</div>
                        <div class="cert-title">{{ $cert->title }}</div>
                    </div>
                </div>

                <span class="type-badge type-{{ $cert->type }}">{{ ucfirst($cert->type) }} Certificate</span>
                <span class="level-badge">🎨 {{ $designs[$cert->design] ?? ucfirst($cert->design) }}</span>
                @if($level)<span class="level-badge">{{ $level['icon'] }} {{ $level['label'] }}</span>@endif

                <div class="meta-row">Issued {{ $cert->issued_at?->format('d M Y') }} &middot; <span class="code">{{ $cert->verify_code }}</span></div>

                <div class="cert-actions">
                    <a href="{{ route('academy.marketing.download', $cert) }}" class="btn">Download PDF</a>
                    <a href="{{ $verifyUrl }}" target="_blank" class="btn-outline">View Public Page</a>
                    <button type="button" class="btn-outline suggest-btn" data-url="{{ route('academy.marketing.suggest-post', $cert) }}" data-target="suggest-{{ $cert->id }}">✨ AI Suggest Post</button>
                    <form method="POST" action="{{ route('academy.marketing.mark-posted', $cert) }}" onsubmit="return confirm('Mark this certificate as posted? It will move to the Already Posted list below.');">
                        @csrf
                        <button type="submit" class="btn-outline">✅ Mark as Posted</button>
                    </form>
                </div>

                <div class="suggest-panel" id="suggest-{{ $cert->id }}">
                    <div class="suggest-field">
                        <div class="lbl">Title <button type="button" class="copy-mini" data-copy-target="title-{{ $cert->id }}">Copy</button></div>
                        <div class="val" id="title-{{ $cert->id }}">-</div>
                    </div>
                    <div class="suggest-field">
                        <div class="lbl">Description <button type="button" class="copy-mini" data-copy-target="desc-{{ $cert->id }}">Copy</button></div>
                        <div class="val" id="desc-{{ $cert->id }}">-</div>
                    </div>
                    <div class="suggest-field">
                        <div class="lbl">Hashtags <button type="button" class="copy-mini" data-copy-target="tags-{{ $cert->id }}">Copy</button></div>
                        <div class="val" id="tags-{{ $cert->id }}">-</div>
                    </div>
                    <div class="suggest-note" id="note-{{ $cert->id }}"></div>
                    <button type="button" class="btn-outline suggest-btn" data-url="{{ route('academy.marketing.suggest-post', $cert) }}" data-target="suggest-{{ $cert->id }}" style="margin-top:4px;">Regenerate</button>
                </div>
            </div>
        @empty
            <div class="empty">No certificates issued yet.</div>
        @endforelse
    </div>

    <div class="pagination-wrap">{{ $certificates->links() }}</div>

    <div class="section-title">Already Posted</div>
    <div class="section-sub">Certificates Marketing has already shared - kept here for reference, out of the way of the to-post list above.</div>

    <div class="gallery">
        @forelse($postedCertificates as $cert)
            @php
                $verifyUrl = route('academy.certificate.verify', $cert->verify_code);
            @endphp
            <div class="cert-card is-posted">
                <div class="cert-head">
                    <img class="photo" src="{{ $cert->user->avatar ? asset('storage/' . $cert->user->avatar) : asset('storage/users/default.png') }}" alt="">
                    <div>
                        <div class="cert-name">{{ $cert->recipient_name }}</div>
                        <div class="cert-title">{{ $cert->title }}</div>
                    </div>
                </div>

                <span class="type-badge type-{{ $cert->type }}">{{ ucfirst($cert->type) }} Certificate</span>
                <span class="posted-badge">✅ Posted {{ $cert->posted_at?->format('d M Y') }}@if($cert->postedBy) by {{ $cert->postedBy->name }}@endif</span>

                <div class="cert-actions">
                    <a href="{{ route('academy.marketing.download', $cert) }}" class="btn-outline">Download PDF</a>
                    <a href="{{ $verifyUrl }}" target="_blank" class="btn-outline">View Public Page</a>
                </div>
            </div>
        @empty
            <div class="empty">Nothing posted yet.</div>
        @endforelse
    </div>

    <div class="pagination-wrap">{{ $postedCertificates->links() }}</div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.suggest-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var panel = document.getElementById(btn.dataset.target);
        panel.style.display = 'block';
        var original = btn.textContent;
        btn.textContent = 'Thinking...';
        btn.disabled = true;

        var id = btn.dataset.target.replace('suggest-', '');
        ['title-', 'desc-', 'tags-'].forEach(function (prefix) {
            var field = document.getElementById(prefix + id);
            field.textContent = 'Generating...';
            field.classList.add('is-loading');
        });
        document.getElementById('note-' + id).textContent = '';

        fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('title-' + id).textContent = data.title;
                document.getElementById('desc-' + id).textContent = data.description;
                document.getElementById('tags-' + id).textContent = data.hashtags;
                var notes = {
                    not_configured: 'No Gemini API key configured yet - showing a simple template instead.',
                    busy: 'Gemini is temporarily busy (high demand) - showing a simple template for now, try Regenerate in a bit.',
                    error: 'Could not reach Gemini right now - showing a simple template instead.'
                };
                document.getElementById('note-' + id).textContent = data.ai_generated
                    ? ''
                    : (notes[data.fallback_reason] || notes.not_configured);
            })
            .catch(function () {
                ['title-', 'desc-', 'tags-'].forEach(function (prefix) {
                    document.getElementById(prefix + id).textContent = '-';
                });
                document.getElementById('note-' + id).textContent = 'Could not generate suggestions - try again.';
            })
            .finally(function () {
                ['title-', 'desc-', 'tags-'].forEach(function (prefix) {
                    document.getElementById(prefix + id).classList.remove('is-loading');
                });
                btn.textContent = original === '✨ AI Suggest Post' ? original : 'Regenerate';
                btn.disabled = false;
            });
    });
});

document.querySelectorAll('.copy-mini').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var text = document.getElementById(btn.dataset.copyTarget).textContent;
        navigator.clipboard.writeText(text).then(function () {
            var original = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function () { btn.textContent = original; }, 1200);
        });
    });
});
</script>
</body>
</html>
