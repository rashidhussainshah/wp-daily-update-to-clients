<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $campaignSubject }}</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
<style>
  body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
  table,td{mso-table-lspace:0pt;mso-table-rspace:0pt}
  img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
  body{margin:0;padding:0;background-color:#f0f2f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif}
  .wrapper{max-width:620px;margin:0 auto}
  .header{background:#1a1a2e;padding:28px 40px;text-align:center}
  .header-brand{color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;text-decoration:none}
  .header-tagline{color:#8892b0;font-size:12px;margin-top:4px}
  .body-cell{background:#ffffff;padding:40px}
  .body-cell p{margin:0 0 16px;line-height:1.7;font-size:15px;color:#2d3748}
  .body-cell ul{margin:0 0 16px;padding-left:24px}
  .body-cell ul li{line-height:1.7;font-size:15px;color:#2d3748;margin-bottom:6px}
  .body-cell h1,.body-cell h2,.body-cell h3{color:#1a1a2e;margin:24px 0 12px}
  .body-cell a{color:#0066cc;text-decoration:underline}
  .btn-cell{text-align:center;padding:8px 40px 32px}
  .btn{display:inline-block;background:#0066cc;color:#ffffff!important;padding:14px 32px;border-radius:6px;text-decoration:none;font-weight:600;font-size:15px;letter-spacing:0.3px}
  .divider{border:none;border-top:1px solid #e8ecef;margin:8px 0}
  .footer-cell{background:#f8fafc;padding:24px 40px;text-align:center;border-top:1px solid #e8ecef}
  .footer-cell p{margin:0 0 6px;font-size:12px;color:#9aa5b4;line-height:1.6}
  .footer-cell a{color:#9aa5b4;text-decoration:none}
  .footer-cell a:hover{text-decoration:underline}
  @media only screen and (max-width:640px){
    .wrapper{width:100%!important}
    .body-cell,.btn-cell,.footer-cell{padding:24px!important}
    .header{padding:20px 24px!important}
  }
</style>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f0f2f5" style="padding:30px 16px;">
<tr><td align="center">
<table class="wrapper" border="0" cellpadding="0" cellspacing="0" width="620" style="border-radius:10px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

  {{-- Header ──────────────────────────────────────────────────────────── --}}
  <tr>
    <td class="header">
      @if($companyLogoUrl)
        <img src="{{ $companyLogoUrl }}" alt="{{ $companyName }}" height="40" style="display:block;margin:0 auto;">
      @else
        <div class="header-brand">{{ $companyName }}</div>
        @if($companyTagline)
          <div class="header-tagline">{{ $companyTagline }}</div>
        @endif
      @endif
    </td>
  </tr>

  {{-- Body ────────────────────────────────────────────────────────────── --}}
  <tr>
    <td class="body-cell">
      {!! $htmlBody !!}
    </td>
  </tr>

  {{-- Footer commented out — triggers "Malicious URL" spam filter rejection
  <tr>
    <td class="footer-cell">
      <p>{{ $unsubscribeText }}</p>
      <p style="margin-top:4px;">
        <a href="mailto:{{ $companyEmail }}?subject=unsubscribe"
           style="color:#9aa5b4;text-decoration:underline;font-size:11px;">
          Click here to unsubscribe
        </a>
      </p>
      <p>
        <strong>{{ $companyName }}</strong>
        @if($companyTagline) &mdash; {{ $companyTagline }}@endif
      </p>
      @if($companyAddress)
        <p>{{ $companyAddress }}</p>
      @endif
      <p>
        <a href="{{ $companyWebsite }}">{{ $companyWebsite }}</a>
        @if($companyEmail)
          &nbsp;&bull;&nbsp; <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a>
        @endif
        @if($companyPhone)
          &nbsp;&bull;&nbsp; {{ $companyPhone }}
        @endif
      </p>
    </td>
  </tr>
  --}}

</table>
</td></tr>
</table>
</body>
</html>
