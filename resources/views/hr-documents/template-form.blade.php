<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $template->exists ? 'Edit Template' : 'New Template' }}</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --line:#e2e8f0; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); }
    .wrap{ max-width:820px; margin:0 auto; padding:40px 24px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .nav-tabs a{ padding:9px 16px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:13px; font-weight:600; background:var(--white); }
    input,textarea,select{ width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13.5px; background:var(--white); color:var(--ink); }
    label{ display:block; font-size:12px; font-weight:700; color:var(--ink-soft); margin-bottom:6px; }
    .field{ margin-bottom:16px; }
    .hint{ color:var(--muted); font-size:12px; margin-top:6px; }
    .row3{ display:grid; grid-template-columns:2fr 1fr 1fr; gap:16px; }
    .checkbox-row{ display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:var(--ink-soft); }
    .checkbox-row input{ width:auto; accent-color:var(--g-600); }
    .token-palette{ display:flex; flex-wrap:wrap; gap:6px; margin-bottom:10px; }
    .token-chip{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); border-radius:999px; padding:4px 10px; font-size:11.5px; font-weight:700; cursor:pointer; font-family:ui-monospace,Menlo,monospace; }
    .token-chip:hover{ background:var(--g-100); }
    #body{ font-family:ui-monospace,Menlo,monospace; font-size:12.5px; min-height:280px; }
    .btn{ display:inline-block; padding:11px 20px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); padding:10px 18px; border-radius:10px; font-weight:700; font-size:13.5px; cursor:pointer; font-family:inherit; }
    .btn-link{ background:none; border:0; color:var(--muted); font-size:13.5px; text-decoration:underline; cursor:pointer; font-family:inherit; padding:10px 6px; }
    .form-actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top:6px; }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .row3{ grid-template-columns:1fr; } .form-actions .btn, .form-actions .btn-outline{ display:block; width:100%; text-align:center; } }
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
        <h1>{{ $template->exists ? 'Edit Template' : 'New Template' }}</h1>
        <div class="sub">Write the letter once with tokens - it's replaced with real data every time it's issued.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('hr-documents.templates.index') }}">&larr; Templates</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $template->exists ? route('hr-documents.templates.update', $template) : route('hr-documents.templates.store') }}" id="template-form">
            @csrf
            @if($template->exists) @method('PUT') @endif

            <div class="row3">
                <div class="field">
                    <label>Template name</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" placeholder="e.g. Experience Letter" required>
                </div>
                <div class="field">
                    <label>Category (free text)</label>
                    <input type="text" name="category" value="{{ old('category', $template->category) }}" placeholder="e.g. experience_letter" required>
                </div>
                <div class="field">
                    <label>Design</label>
                    <select name="design" id="design">
                        @foreach($designs as $key => $label)
                            <option value="{{ $key }}" @selected(old('design', $template->design) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field">
                <label class="checkbox-row">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active))>
                    Active (visible when issuing letters - uncheck to keep as a draft)
                </label>
            </div>

            <div class="field">
                <label>Letter body (HTML) - click a token below to insert it at the cursor</label>
                <div class="token-palette">
                    @foreach($tokens as $key => $description)
                        <span class="token-chip" data-token="{!! '{{'.$key.'}}' !!}" title="{{ $description }}">{!! '{{'.$key.'}}' !!}</span>
                    @endforeach
                </div>
                <textarea name="body" id="body" required>{{ old('body', $template->body) }}</textarea>
                <p class="hint">Write the full letter (salutation, body, closing) as HTML paragraphs. Tokens like <code>{!! '{{employee_name}}' !!}</code> are replaced with real data when the letter is issued.</p>
            </div>

            <div class="field" style="max-width:320px;">
                <label>Preview with</label>
                <select id="preview-employee">
                    <option value="">Sample placeholder data</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save Template</button>
                <button type="button" class="btn-outline" id="preview-btn">Preview PDF</button>
                <a href="{{ route('hr-documents.templates.index') }}" class="btn-link">Cancel</a>
            </div>
        </form>

        <form method="POST" action="{{ route('hr-documents.templates.preview') }}" target="_blank" id="preview-form" style="display:none;">
            @csrf
            <input type="hidden" name="design" id="preview-design">
            <input type="hidden" name="body" id="preview-body">
            <input type="hidden" name="user_id" id="preview-user-id">
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.token-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
        var textarea = document.getElementById('body');
        var token = chip.dataset.token;
        var start = textarea.selectionStart ?? textarea.value.length;
        var end = textarea.selectionEnd ?? textarea.value.length;
        textarea.value = textarea.value.slice(0, start) + token + textarea.value.slice(end);
        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = start + token.length;
    });
});

document.getElementById('preview-btn').addEventListener('click', function () {
    document.getElementById('preview-design').value = document.getElementById('design').value;
    document.getElementById('preview-body').value = document.getElementById('body').value;
    document.getElementById('preview-user-id').value = document.getElementById('preview-employee').value;
    document.getElementById('preview-form').submit();
});
</script>
</body>
</html>
