<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Join Webpenter IT Academy</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --muted:#64748b; --line:#e2e8f0; --g-50:#f0fdf4; --g-100:#dcfce7; --g-600:#16a34a; --g-700:#15803d; --g-500:#22c55e; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#f8faf9; }
    .wrap{ max-width:560px; margin:0 auto; padding:56px 24px; }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:28px; }
    .logo{ width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; }
    h1{ font-size:24px; font-weight:800; color:var(--ink); margin:0 0 6px; }
    p.sub{ color:var(--muted); font-size:13px; margin:0 0 24px; }
    label{ display:block; font-size:12.5px; font-weight:700; color:#334155; margin-bottom:6px; }
    input, select{ width:100%; padding:11px 13px; border:1.5px solid var(--line); border-radius:10px; font-size:14px; font-family:inherit; margin-bottom:16px; }
    input:focus, select:focus{ outline:none; border-color:var(--g-600); box-shadow:0 0 0 3px var(--g-100); }
    button{ width:100%; padding:13px; border:0; border-radius:10px; background:linear-gradient(135deg,var(--g-700),var(--g-500)); color:#fff; font-weight:700; font-size:14.5px; cursor:pointer; }
    .error{ background:#fef2f2; color:#dc2626; padding:12px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }
    .empty{ text-align:center; padding:40px 20px; color:var(--muted); background:#fff; border:1px solid var(--line); border-radius:14px; }
</style>
</head>
<body>
<div class="wrap">
    <div class="brand"><div class="logo">WP</div><strong>Webpenter IT Academy</strong></div>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    @if($tracks->isEmpty())
        <div class="empty">No tracks are currently open for enrollment. Please check back soon.</div>
    @else
        <h1>Register for a Track</h1>
        <p class="sub">Takes about 2 minutes. You'll get portal access right after.</p>

        <form method="POST" action="{{ route('academy.register.store') }}">
            @csrf
            <label>Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}">

            <label>Password</label>
            <input type="password" name="password" required minlength="8">

            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required minlength="8">

            <label>Choose a Track</label>
            <select name="track_id" required>
                <option value="">Select a track...</option>
                @foreach($tracks as $track)
                    <option value="{{ $track->id }}" @selected(old('track_id') == $track->id)>{{ $track->name }}</option>
                @endforeach
            </select>

            <button type="submit">Register</button>
        </form>
    @endif
</div>
</body>
</html>
