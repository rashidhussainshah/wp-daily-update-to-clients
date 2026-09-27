<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Academy ID Cards</title>
<style>
    @page { margin: 12mm; }
    body { margin: 0; font-family: 'DejaVu Sans', sans-serif; background: #f1f5f9; }
    .toolbar { padding: 16px; text-align: center; }
    .toolbar button { padding: 10px 22px; border: 0; border-radius: 8px; background: #16a34a; color: #fff; font-weight: 700; font-size: 14px; cursor: pointer; }
    .toolbar .count { display: block; margin-top: 8px; font-size: 12.5px; color: #64748b; }
    .sheet { display: flex; flex-wrap: wrap; gap: 14px; justify-content: center; padding: 0 16px 30px; }

    /* Vertical (portrait) ID card - standard CR80 card proportions scaled up for readability/printing. */
    .card { width: 214px; height: 330px; border-radius: 16px; background: #fffefb; border: 1.5px solid #dcfce7; position: relative; overflow: hidden; padding: 18px 16px 16px; box-sizing: border-box; page-break-inside: avoid; box-shadow: 0 2px 6px rgba(15,23,42,.08); display: flex; flex-direction: column; align-items: center; text-align: center; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, #15803d, #22c55e); }
    .brand { font-size: 13px; font-weight: 800; color: #0f172a; margin-top: 6px; }
    .brand-sub { font-size: 7.5px; letter-spacing: 1.2px; color: #64748b; }
    .label { font-size: 7.5px; letter-spacing: 1.5px; color: #15803d; font-weight: 700; margin-top: 6px; }

    .photo { width: 92px; height: 92px; border-radius: 50%; object-fit: cover; border: 3px solid #dcfce7; margin-top: 14px; }
    .name { font-size: 15px; font-weight: 800; color: #0f172a; margin-top: 12px; }
    .track { font-size: 10px; color: #64748b; margin-top: 3px; max-width: 170px; }
    .student-id { font-size: 9px; color: #94a3b8; margin-top: 6px; }
    .level { display: inline-block; margin-top: 8px; padding: 3px 10px; border-radius: 999px; background: #f0fdf4; color: #15803d; font-size: 8.5px; font-weight: 700; }

    .qr { margin-top: auto; padding-top: 10px; text-align: center; }
    .qr img { width: 58px; height: 58px; }
    .qr-label { font-size: 6px; color: #94a3b8; margin-top: 3px; letter-spacing: .5px; }

    @media print {
        body { background: #fff; }
        .toolbar { display: none; }
        .sheet { gap: 8mm; }
    }
</style>
</head>
<body>
<div class="toolbar">
    <button onclick="window.print()">Print</button>
    <span class="count">{{ $students->count() }} card{{ $students->count() === 1 ? '' : 's' }} in this batch</span>
</div>
<div class="sheet">
    @foreach($students as $student)
        @php
            $enrollment = $student->academyEnrollments->first();
            $track = $enrollment?->track;
            $level = $enrollment?->levelTier() ?? ['icon' => '🌱', 'label' => 'New Talent'];
            $badgeUrl = $enrollment ? route('academy.student-badge.show', $enrollment->parent_view_token) : url('/');
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($badgeUrl);
            $photoUrl = $student->avatar ? asset('storage/' . $student->avatar) : asset('storage/users/default.png');
        @endphp
        <div class="card">
            <div class="brand">Webpenter</div>
            <div class="brand-sub">SOFTWARE &amp; TRAINING</div>
            <div class="label">IT ACADEMY</div>
            <img class="photo" src="{{ $photoUrl }}" alt="{{ $student->name }}">
            <div class="name">{{ $student->name }}</div>
            <div class="track">{{ $track->name ?? 'Not enrolled' }}</div>
            <div class="student-id">Student ID: WP-STU-{{ $student->id }}</div>
            <div class="level">{{ $level['icon'] }} {{ $level['label'] }}</div>
            <div class="qr">
                <img src="{{ $qrUrl }}" alt="QR code">
                <div class="qr-label">SCAN TO VERIFY</div>
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
