<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 landscape; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #ffffff; }
    /* Light + green, matching the Classic/LinkedIn designs' palette - a
       solid dark banner read as off-brand next to them. */
    .banner { background: #ffffff; padding: 26px 64px 22px; border-bottom: 3px solid #16a34a; }
    .banner-row { display: flex; justify-content: space-between; align-items: center; }
    .logo { font-size: 18px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #64748b; margin-top: 2px; }
    .cert-tag { display: inline-block; padding: 6px 16px; border-radius: 4px; background: #16a34a; color: #ffffff; font-size: 10px; font-weight: bold; letter-spacing: 1.5px; }
    .frame { padding: 40px 64px; }
    .center { text-align: center; }
    .eyebrow { font-size: 11px; letter-spacing: 2px; color: #15803d; font-weight: bold; margin-top: 6px; }
    h1 { font-size: 34px; color: #0f172a; margin: 12px 0 6px; font-weight: bold; }
    .certify { font-size: 13px; color: #57606a; }
    .name { font-size: 34px; color: #0f172a; font-weight: bold; margin: 10px 0 6px; border-bottom: 3px solid #16a34a; display: inline-block; padding-bottom: 6px; }
    h2 { font-size: 20px; color: #0f172a; margin: 16px 0 4px; font-weight: bold; }
    .meta { font-size: 10px; color: #57606a; margin-top: 14px; }
    .achievement { font-size: 11.5px; color: #334155; font-style: italic; max-width: 600px; margin: 8px auto 0; line-height: 1.5; }
    /* Normal flow, not position:fixed - fixed pins to a spot near the
       physical page bottom regardless of how much content is above it,
       which left a big dead gap for a shorter certificate. This way the
       footer sits a fixed, small distance below the actual content. */
    .footer { margin: 70px 64px 0; }
    /* table layout for the two signatures + verify line - reliable
       side-by-side columns in dompdf, unlike the flex it replaced. */
    .footer-table { display: table; width: 100%; }
    .footer-row { display: table-row; }
    .sig-col { display: table-cell; vertical-align: bottom; padding-right: 50px; white-space: nowrap; }
    .verify-col { display: table-cell; vertical-align: bottom; text-align: right; width: 100%; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #d1d5db; padding-top: 6px; }
    .sig-image { max-height: 38px; margin-bottom: 4px; }
    .sig-title { font-size: 8.5px; letter-spacing: 0.5px; color: #57606a; margin-top: 2px; }
    .verify { font-size: 9px; color: #9ca3af; }
</style>
</head>
<body>
<div class="banner">
    <div class="banner-row">
        <div>
            <div class="logo">Webpenter</div>
            <div class="tagline">Software &amp; Training &middot; Pakistan</div>
        </div>
        <span class="cert-tag">{{ strtoupper($companyLabel) }}</span>
    </div>
</div>

<div class="frame">
    <div class="center">
        <div class="eyebrow">{{ $type === 'track' ? 'CERTIFICATE OF COMPLETION' : 'CERTIFICATE OF ACHIEVEMENT' }}</div>
        <h1>{{ $title }}</h1>
        <div class="certify">This is to certify that</div>
        <div class="name">{{ $recipientName }}</div>
        <div class="certify">{{ $type === 'track' ? 'has successfully completed all requirements of this program' : 'has successfully completed this course' }} through {{ $companyLabel }}.</div>
        @if($achievementNote)
            <div class="achievement">{{ $achievementNote }}</div>
        @endif
        <div class="meta">Issued {{ $issuedAt->format('F j, Y') }} &middot; Certificate ID {{ $verifyCode }}@if($instructorName) &middot; Instructor: {{ $instructorName }}@endif</div>
    </div>
</div>

<div class="footer">
    <div class="footer-table">
        <div class="footer-row">
            @if($signatory1Name || $signatory1ImageUrl)
            <div class="sig-col">
                @if($signatory1ImageUrl)
                    <img src="{{ $signatory1ImageUrl }}" class="sig-image" alt="Signature">
                @else
                    <div class="sig-name">{{ $signatory1Name }}</div>
                @endif
                <div class="sig-title">{{ strtoupper($signatory1Title) }}, WEBPENTER</div>
            </div>
            @endif
            @if($signatory2Name || $signatory2ImageUrl)
            <div class="sig-col">
                @if($signatory2ImageUrl)
                    <img src="{{ $signatory2ImageUrl }}" class="sig-image" alt="Signature">
                @else
                    <div class="sig-name">{{ $signatory2Name }}</div>
                @endif
                <div class="sig-title">{{ strtoupper($signatory2Title) }}, WEBPENTER</div>
            </div>
            @endif
            <div class="verify-col">
                <div class="verify">Verify at<br>{{ $verifyUrl }}</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
