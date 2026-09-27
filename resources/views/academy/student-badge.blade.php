<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $enrollment->user->name }} - WebPenter IT Academy</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --muted:#64748b; --g-50:#f0fdf4; --g-100:#dcfce7; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#f8faf9; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:24px; }
    .card{ background:#fff; border-radius:20px; padding:32px; max-width:360px; width:100%; text-align:center; border:1px solid #e2e8f0; box-shadow:0 12px 32px rgba(15,23,42,.08); }
    .photo{ width:96px; height:96px; border-radius:50%; object-fit:cover; border:3px solid var(--g-100); margin-bottom:14px; }
    .name{ font-size:20px; font-weight:800; color:var(--ink); }
    .track{ color:var(--muted); font-size:13px; margin-top:4px; }
    .level{ display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:999px; background:var(--g-50); border:1.5px solid var(--g-100); color:var(--g-700); font-weight:800; font-size:13px; margin-top:16px; }
    .verified{ margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0; color:var(--g-700); font-size:12px; font-weight:700; }
    .brand{ color:var(--muted); font-size:11px; margin-top:6px; }
</style>
</head>
<body>
<div class="card">
    <img class="photo" src="{{ $enrollment->user->avatar ? asset('storage/' . $enrollment->user->avatar) : asset('storage/users/default.png') }}" alt="{{ $enrollment->user->name }}">
    <div class="name">{{ $enrollment->user->name }}</div>
    <div class="track">{{ $enrollment->track->name }}</div>
    <div class="level">{{ $level['icon'] }} {{ $level['label'] }}</div>
    <div class="verified">&#10003; Verified WebPenter IT Academy Student</div>
    <div class="brand">webpenter.com</div>
</div>
</body>
</html>
