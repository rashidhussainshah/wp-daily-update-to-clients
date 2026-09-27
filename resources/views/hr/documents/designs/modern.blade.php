<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 portrait; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #ffffff; color: #1e293b; }
    .top-bar { position: fixed; top: 0; left: 0; right: 0; height: 8px; background: #16a34a; }
    .banner { padding: 30px 56px 18px; }
    .banner-row { display: table; width: 100%; }
    .banner-cell { display: table-cell; vertical-align: middle; }
    .logo { font-size: 18px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #64748b; margin-top: 2px; }
    .cert-tag { display: inline-block; padding: 5px 14px; border-radius: 4px; background: #16a34a; color: #ffffff; font-size: 9.5px; font-weight: bold; letter-spacing: 1.2px; float: right; }
    .frame { padding: 6px 56px 46px; }
    .letter-body { font-size: 12.5px; line-height: 1.75; color: #1e293b; margin-top: 14px; }
    .letter-body p { margin: 0 0 12px; }
    {{-- Normal flow with margin-top, not a fixed footer - avoids a big dead
         gap under a short letter (fixed pins to the physical page bottom
         regardless of content height). --}}
    .footer { margin: 60px 56px 0; }
    {{-- table layout, not flex - reliable side-by-side columns in dompdf. --}}
    .footer-table { display: table; width: 100%; }
    .footer-row { display: table-row; }
    .sig-col { display: table-cell; vertical-align: bottom; white-space: nowrap; padding-right: 46px; }
    .verify-col { display: table-cell; vertical-align: bottom; text-align: right; width: 100%; }
    .sig-image { max-height: 38px; margin-bottom: 4px; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #d1d5db; padding-top: 6px; min-width: 150px; }
    .sig-title { font-size: 8.5px; letter-spacing: 0.5px; color: #64748b; margin-top: 2px; }
    .verify { font-size: 8.5px; color: #9ca3af; }
</style>
</head>
<body>
<div class="top-bar"></div>
<div class="banner">
    <div class="banner-row">
        <div class="banner-cell">
            <div class="logo">{{ $tokens['company_name'] ?? 'WebPenter' }}</div>
            <div class="tagline">Software &amp; Training &middot; Pakistan</div>
        </div>
        <div class="banner-cell"><span class="cert-tag">HUMAN RESOURCES</span></div>
    </div>
</div>

<div class="frame">
    <div class="letter-body">{!! $bodyHtml !!}</div>

    <div class="footer">
        <div class="footer-table">
            <div class="footer-row">
                @include('hr.documents.designs.partials.signatures', ['signatories' => $signatories])
                <div class="verify-col">
                    @if($verifyUrl)
                        <div class="verify">Verify at {{ $verifyUrl }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
