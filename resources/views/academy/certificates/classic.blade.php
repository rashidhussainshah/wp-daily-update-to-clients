<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 landscape; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #fffefb; }
    .border { position: fixed; top: 18px; left: 18px; right: 18px; bottom: 18px; border: 1.5px solid #16a34a; opacity: 0.55; }
    .border-inner { position: fixed; top: 24px; left: 24px; right: 24px; bottom: 24px; border: 1px solid #16a34a; opacity: 0.3; }
    .frame { padding: 50px 70px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; }
    .logo { font-size: 16px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #64748b; }
    .label { font-size: 10px; letter-spacing: 2px; color: #15803d; font-weight: bold; }
    .center { text-align: center; margin-top: 30px; }
    .eyebrow { font-size: 11px; letter-spacing: 3px; color: #64748b; font-weight: bold; }
    h1 { font-family: 'Times New Roman', serif; font-weight: bold; font-size: 40px; color: #0f172a; margin: 14px 0; }
    .certify { font-style: italic; font-size: 14px; color: #334155; }
    .name { font-family: 'Times New Roman', serif; font-style: italic; font-size: 46px; color: #15803d; margin: 12px 0 4px; }
    .rule { width: 260px; height: 1px; background: #0f172a; opacity: 0.15; margin: 0 auto 18px; }
    h2 { font-family: 'Times New Roman', serif; font-weight: bold; font-size: 24px; color: #0f172a; margin: 10px 0; }
    .pill { display: inline-block; padding: 5px 14px; border-radius: 999px; background: #dcfce7; color: #15803d; font-size: 10px; font-weight: bold; }
    .achievement { font-size: 11.5px; color: #334155; font-style: italic; max-width: 560px; margin: 10px auto 0; line-height: 1.5; }
    .meta { font-size: 10.5px; color: #64748b; margin-top: 18px; }
    .signatures { display: flex; justify-content: center; gap: 100px; margin-top: 50px; }
    .sig { width: 180px; text-align: center; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #0f172a; padding-top: 6px; }
    .sig-image { max-height: 40px; margin-bottom: 4px; }
    .sig-title { font-size: 9px; letter-spacing: 1px; color: #64748b; margin-top: 2px; }
    .verify { text-align: center; margin-top: 26px; font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>
<div class="frame">
    <div class="border"></div>
    <div class="border-inner"></div>

    <div class="header">
        <div>
            <div class="logo">Webpenter</div>
            <div class="tagline">Software &amp; Training &middot; Pakistan</div>
        </div>
        <div class="label">{{ strtoupper($companyLabel) }}</div>
    </div>

    <div class="center">
        <div class="eyebrow">{{ strtoupper($companyLabel) }} PRESENTS</div>
        <h1>{{ $type === 'track' ? 'Certificate of Completion' : 'Certificate of Achievement' }}</h1>
        <div class="certify">This is to certify that</div>
        <div class="name">{{ $recipientName }}</div>
        <div class="rule"></div>
        <div class="certify">has successfully completed {{ $type === 'track' ? 'all requirements of the' : '' }}</div>
        <h2>{{ $title }}</h2>
        <span class="pill">{{ strtoupper($companyLabel) }}</span>
        @if($achievementNote)
            <div class="achievement">{{ $achievementNote }}</div>
        @endif
        <div class="meta">Issued on {{ $issuedAt->format('F j, Y') }} &middot; Certificate ID: {{ $verifyCode }}@if($instructorName) &middot; Instructor: {{ $instructorName }}@endif</div>
    </div>

    <div class="signatures">
        @if($signatory1Name || $signatory1ImageUrl)
        <div class="sig">
            @if($signatory1ImageUrl)
                <img src="{{ $signatory1ImageUrl }}" class="sig-image" alt="Signature">
            @else
                <div class="sig-name">{{ $signatory1Name }}</div>
            @endif
            <div class="sig-title">{{ strtoupper($signatory1Title) }}</div>
        </div>
        @endif
        @if($signatory2Name || $signatory2ImageUrl)
        <div class="sig">
            @if($signatory2ImageUrl)
                <img src="{{ $signatory2ImageUrl }}" class="sig-image" alt="Signature">
            @else
                <div class="sig-name">{{ $signatory2Name }}</div>
            @endif
            <div class="sig-title">{{ strtoupper($signatory2Title) }}</div>
        </div>
        @endif
    </div>

    <div class="verify">Verify at {{ $verifyUrl }}</div>
</div>
</body>
</html>
