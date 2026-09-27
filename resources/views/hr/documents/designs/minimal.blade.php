<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { size: A4 portrait; margin: 0; }
    body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; background: #ffffff; color: #1e293b; }
    .frame { padding: 54px 60px; }
    .header { display: table; width: 100%; margin-bottom: 20px; }
    .header-row { display: table-row; }
    .header-cell { display: table-cell; vertical-align: top; }
    .logo { font-size: 15px; font-weight: bold; color: #0f172a; }
    .tagline { font-size: 9px; color: #94a3b8; margin-top: 1px; }
    .dept-label { text-align: right; font-size: 9px; color: #94a3b8; }
    .rule { height: 1px; background: #e2e8f0; margin: 0 0 24px; }
    .letter-body { font-size: 12.5px; line-height: 1.8; color: #1e293b; }
    .letter-body p { margin: 0 0 12px; }
    {{-- Normal flow, not a fixed footer - keeps the signature line close to
         the actual content instead of pinned to the physical page bottom. --}}
    .footer { margin: 70px 0 0; }
    {{-- table layout, not flex - reliable side-by-side columns in dompdf. --}}
    .footer-table { display: table; width: 100%; }
    .footer-row { display: table-row; }
    .sig-col { display: table-cell; vertical-align: bottom; white-space: nowrap; padding-right: 46px; }
    .sig-image { max-height: 36px; margin-bottom: 4px; }
    .sig-name { font-size: 12px; font-weight: bold; color: #0f172a; border-top: 1px solid #cbd5e1; padding-top: 6px; min-width: 180px; }
    .sig-title { font-size: 9px; color: #64748b; margin-top: 2px; }
    .verify { font-size: 8.5px; color: #cbd5e1; margin-top: 20px; }
</style>
</head>
<body>
<div class="frame">
    <div class="header">
        <div class="header-row">
            <div class="header-cell">
                <div class="logo">{{ $tokens['company_name'] ?? 'WebPenter' }}</div>
                <div class="tagline">Software &amp; Training &middot; Pakistan</div>
            </div>
            <div class="header-cell dept-label">Human Resources</div>
        </div>
    </div>
    <div class="rule"></div>

    <div class="letter-body">{!! $bodyHtml !!}</div>

    <div class="footer">
        <div class="footer-table">
            <div class="footer-row">
                @include('hr.documents.designs.partials.signatures', ['signatories' => $signatories])
            </div>
        </div>
        @if($verifyUrl)
            <div class="verify">Verify at {{ $verifyUrl }}</div>
        @endif
    </div>
</div>
</body>
</html>
