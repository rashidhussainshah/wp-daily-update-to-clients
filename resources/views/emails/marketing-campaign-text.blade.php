{{ $textBody }}

---
{{ $companyName }}{{ $companyTagline ? ' — '.$companyTagline : '' }}
{{ $companyAddress }}
{{ $companyWebsite }}{{ $companyEmail ? ' | '.$companyEmail : '' }}{{ $companyPhone ? ' | '.$companyPhone : '' }}

{{ $unsubscribeText }}
To unsubscribe, email {{ $companyEmail }} with subject "unsubscribe".
