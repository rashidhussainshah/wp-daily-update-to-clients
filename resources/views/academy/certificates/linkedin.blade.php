<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 landscape; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #ffffff; }
    .frame { padding: 46px 64px; }
    .top-bar { position: fixed; top: 0; left: 0; right: 0; height: 10px; background: #16a34a; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 10px; }
    .logo { font-size: 17px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #64748b; margin-top: 2px; }
    .label { font-size: 9.5px; letter-spacing: 2px; color: #15803d; font-weight: bold; }
    .body-row { margin-top: 34px; }
    .eyebrow { font-size: 10.5px; letter-spacing: 2.5px; color: #64748b; font-weight: bold; }
    h1 { font-size: 30px; color: #0f172a; margin: 10px 0 4px; font-weight: bold; }
    .certify { font-size: 13px; color: #334155; margin-top: 6px; }
    .name { font-size: 32px; color: #15803d; font-weight: bold; margin: 6px 0 10px; }
    h2 { font-size: 20px; color: #0f172a; margin: 8px 0; font-weight: bold; }
    .meta { font-size: 10px; color: #64748b; margin-top: 16px; }
    .achievement { font-size: 11.5px; color: #334155; font-style: italic; max-width: 620px; margin-top: 8px; line-height: 1.5; }
    /* Normal flow, not position:fixed - fixed pins to a spot near the
       physical page bottom regardless of how much content is above it,
       which left a big dead gap for a shorter certificate. This way the
       footer sits a fixed, small distance below the actual content. */
    .footer { margin: 60px 0 0; }
    /* table layout for the two signatures + verify line - reliable
       side-by-side columns in dompdf, unlike nested flex here. */
    .footer-table { display: table; width: 100%; }
    .footer-row { display: table-row; }
    .sig-col { display: table-cell; vertical-align: bottom; padding-right: 50px; white-space: nowrap; }
    .verify-col { display: table-cell; vertical-align: bottom; text-align: right; width: 100%; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #cbd5e1; padding-top: 6px; }
    .sig-image { max-height: 38px; margin-bottom: 4px; }
    .sig-title { font-size: 8.5px; letter-spacing: 0.5px; color: #64748b; margin-top: 2px; }
    .verify { font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>
<div class="top-bar"></div>
<div class="frame">
    <div class="header">
        <div>
            <div class="logo">Webpenter</div>
            <div class="tagline">Software &amp; Training &middot; Pakistan</div>
        </div>
        <div class="label">{{ strtoupper($companyLabel) }}</div>
    </div>

    <div class="body-row">
        <div class="eyebrow">{{ $type === 'track' ? 'CERTIFICATE OF COMPLETION' : 'CERTIFICATE OF ACHIEVEMENT' }}</div>
        <h1>{{ $title }}</h1>
        <div class="certify">This certifies that</div>
        <div class="name">{{ $recipientName }}</div>
        <div class="certify">{{ $type === 'track' ? 'has successfully completed all requirements of this program' : 'has successfully completed this course' }}, demonstrating practical, job-ready skills.</div>
        @if($achievementNote)
            <div class="achievement">{{ $achievementNote }}</div>
        @endif
        <div class="meta">Issued {{ $issuedAt->format('F j, Y') }} &middot; Credential ID {{ $verifyCode }}@if($instructorName) &middot; Instructor: {{ $instructorName }}@endif</div>
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
                    <div class="verify">Verify this credential at<br>{{ $verifyUrl }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
