<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Issue a Letter</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --line:#e2e8f0; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); }
    .wrap{ max-width:820px; margin:0 auto; padding:40px 24px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .step-title{ font-size:15px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .nav-tabs a{ padding:9px 16px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:13px; font-weight:600; background:var(--white); }
    .status-flash{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13.5px; }
    .status-flash a{ color:var(--g-700); font-weight:700; }
    input,textarea,select{ width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:10px; font-family:inherit; font-size:13.5px; margin-bottom:0; background:var(--white); color:var(--ink); }
    label{ display:block; font-size:12px; font-weight:700; color:var(--ink-soft); margin-bottom:6px; }
    .field{ margin-bottom:14px; }
    .hint{ color:var(--muted); font-size:12px; margin-bottom:14px; }
    .mode-toggle{ display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
    .mode-opt{ flex:1; min-width:200px; display:flex; align-items:center; gap:10px; padding:12px 14px; border:1.5px solid var(--line); border-radius:12px; cursor:pointer; font-size:13px; font-weight:600; color:var(--ink-soft); }
    .mode-opt input{ width:auto; margin:0; accent-color:var(--g-600); }
    .mode-opt.sel{ border-color:var(--g-600); background:var(--g-50); color:var(--g-700); }
    .row2{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .btn{ display:inline-block; padding:11px 20px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none; }
    .btn-outline{ background:none; border:1.5px solid var(--line); color:var(--ink-soft); padding:10px 18px; border-radius:10px; font-weight:700; font-size:13.5px; cursor:pointer; font-family:inherit; }
    .form-actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top:6px; }
    .info-note{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); padding:10px 14px; border-radius:10px; font-size:12.5px; margin-bottom:16px; }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } .row2{ grid-template-columns:1fr; } .form-actions .btn, .form-actions .btn-outline{ display:block; width:100%; text-align:center; } }
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
        <h1>Issue a Letter</h1>
        <div class="sub">Pick a recipient and a template, confirm the details, then generate.</div>
    </div>

    <div class="nav-tabs">
        <a href="{{ route('hr-documents.dashboard') }}">&larr; Dashboard</a>
        <a href="{{ route('hr-documents.templates.index') }}">Templates</a>
        <a href="{{ route('hr-documents.issuances.index') }}">History</a>
    </div>

    @if(session('status'))
        <div class="status-flash">{{ session('status') }} &middot; <a href="{{ route('hr-documents.issuances.index') }}">View in History</a></div>
    @endif

    <div class="card">
        <div class="step-title">1. Choose recipient &amp; template</div>
        <form method="GET" action="{{ route('hr-documents.issue.create') }}" id="pick-form">
            <div class="mode-toggle">
                <label class="mode-opt {{ $mode === 'existing' ? 'sel' : '' }}">
                    <input type="radio" name="mode" value="existing" @checked($mode === 'existing') onchange="this.form.submit()"> Select existing user
                </label>
                <label class="mode-opt {{ $mode === 'manual' ? 'sel' : '' }}">
                    <input type="radio" name="mode" value="manual" @checked($mode === 'manual') onchange="this.form.submit()"> Enter manually
                </label>
            </div>

            <div class="row2">
                @if($mode === 'existing')
                    <div class="field">
                        <label>Employee</label>
                        <select name="employee_id" onchange="this.form.submit()">
                            <option value="">Select...</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected($employee && $employee->id === $emp->id)>{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="field">
                        <label>&nbsp;</label>
                        <div class="hint" style="margin-top:10px;">Recipient name &amp; email will be typed directly into the fields below - nothing here is auto-filled.</div>
                    </div>
                @endif
                <div class="field">
                    <label>Template</label>
                    <select name="template_id" onchange="this.form.submit()">
                        <option value="">Select...</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" @selected($template && $template->id === $tpl->id)>{{ $tpl->name }} ({{ $tpl->category }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <noscript><button type="submit" class="btn-outline">Load</button></noscript>
        </form>
    </div>

    @if($template && $tokens && ($mode === 'manual' || $employee))
        <div class="card">
            <div class="step-title">2. Review &amp; confirm the letter details</div>
            @if($mode === 'existing')
                <div class="info-note">Fields pulled from {{ $employee->name }}'s record are pre-filled - check them and fill in anything that couldn't be auto-filled (e.g. last working day, purpose) before generating.</div>
            @else
                <div class="info-note">Fill in the recipient's details manually - nothing is auto-filled since this recipient isn't a user in the system.</div>
            @endif

            <form method="POST" action="{{ route('hr-documents.issue.store') }}" id="issue-form">
                @csrf
                <input type="hidden" name="mode" value="{{ $mode }}">
                @if($mode === 'existing')
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                @endif
                <input type="hidden" name="template_id" value="{{ $template->id }}">

                <div class="row2">
                    @foreach($tokenDictionary as $key => $description)
                        <div class="field">
                            <label>{!! '{{'.$key.'}}' !!} <span style="font-weight:400; color:var(--muted); text-transform:none; letter-spacing:0;">&middot; {{ $description }}</span></label>
                            <input type="text" name="tokens[{{ $key }}]" value="{{ old('tokens.'.$key, $tokens[$key] ?? '') }}" @if($key === 'employee_name') required @endif>
                        </div>
                    @endforeach
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Generate &amp; Save</button>
                    <button type="button" class="btn-outline" id="preview-btn">Preview PDF</button>
                </div>
            </form>

            <form method="POST" action="{{ route('hr-documents.issue.preview') }}" target="_blank" id="preview-form" style="display:none;"></form>
        </div>
    @elseif($employee || $template || $mode === 'manual')
        <div class="info-note">Select a template{{ $mode === 'existing' ? ' and an employee' : '' }} to continue.</div>
    @endif
</div>

<script>
var previewBtn = document.getElementById('preview-btn');
if (previewBtn) {
    previewBtn.addEventListener('click', function () {
        var previewForm = document.getElementById('preview-form');
        previewForm.innerHTML = '';
        var mainForm = document.getElementById('issue-form');
        new FormData(mainForm).forEach(function (value, key) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            previewForm.appendChild(input);
        });
        previewForm.submit();
    });
}
</script>
</body>
</html>
