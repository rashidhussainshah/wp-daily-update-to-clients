<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 portrait; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #fffefb; color: #1e293b; }
    {{-- position:fixed pins to the page box itself in dompdf, with no parent
         height needed - overflow:hidden + height:100% tricks add a phantom
         blank extra page instead, so avoid those for a full-page border. --}}
    .border { position: fixed; top: 16px; left: 16px; right: 16px; bottom: 16px; border: 1.5px solid #16a34a; opacity: 0.55; }
    .border-inner { position: fixed; top: 21px; left: 21px; right: 21px; bottom: 21px; border: 1px solid #16a34a; opacity: 0.3; }
    .frame { padding: 46px 56px; }
    .header { display: table; width: 100%; margin-bottom: 24px; }
    .header-row { display: table-row; }
    .header-cell { display: table-cell; vertical-align: top; }
    .logo { font-size: 17px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #64748b; margin-top: 2px; }
    .dept-label { text-align: right; font-size: 9.5px; letter-spacing: 1.5px; color: #15803d; font-weight: bold; }
    .rule { height: 2px; background: #16a34a; opacity: 0.5; margin: 14px 0 26px; }
    .letter-body { font-size: 12.5px; line-height: 1.75; color: #1e293b; }
    .letter-body p { margin: 0 0 12px; }
    {{-- Normal flow with margin-top, not position:fixed near the bottom -
         a fixed footer leaves a large dead gap for a short letter, since it
         pins to the physical page bottom regardless of content height. --}}
    .footer { margin: 60px 0 0; }
    {{-- table layout, not flex - dompdf lays flex out unreliably for
         side-by-side signature/verify columns. --}}
    .footer-table { display: table; width: 100%; }
    .footer-row { display: table-row; }
    .sig-col { display: table-cell; vertical-align: bottom; white-space: nowrap; padding-right: 46px; }
    .verify-col { display: table-cell; vertical-align: bottom; text-align: right; width: 100%; }
    .sig-image { max-height: 38px; margin-bottom: 4px; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #0f172a; padding-top: 6px; min-width: 150px; }
    .sig-title { font-size: 9px; letter-spacing: 0.5px; color: #64748b; margin-top: 2px; }
    .verify { font-size: 8.5px; color: #94a3b8; }
</style>
</head>
<body>
<div class="border"></div>
<div class="border-inner"></div>
<div class="frame">
    <div class="header">
        <div class="header-row">
            <div class="header-cell">
                <div class="logo">{{ $tokens['company_name'] ?? 'WebPenter' }}</div>
                <div class="tagline">Software &amp; Training &middot; Pakistan</div>
            </div>
            <div class="header-cell dept-label">HUMAN RESOURCES</div>
        </div>
    </div>
    <div class="rule"></div>

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
