@php $accent = e($sig->accent_color ?: '#1a1a2e'); @endphp
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;border-top:1px solid #e2e8f0;margin-top:28px;">
<tr><td style="padding-top:14px;">
  @if($sig->handwritten_url)
    <img src="{{ $sig->handwritten_url }}" alt="signature" style="max-height:38px;display:block;margin-bottom:8px;">
  @endif
  <div style="font-size:15px;font-weight:700;color:{{ $accent }};font-family:Arial,sans-serif;line-height:1.2;">{{ e($sig->display_name) }}</div>
  @if($sig->designation)
    <div style="font-size:12px;color:#777777;margin-top:3px;font-family:Arial,sans-serif;">{{ e($sig->designation) }}</div>
  @endif
  @if($sig->tagline)
    <div style="font-size:11px;color:#aaaaaa;margin-top:2px;font-style:italic;font-family:Arial,sans-serif;">{{ e($sig->tagline) }}</div>
  @endif
  <div style="font-size:12px;color:#888888;margin-top:9px;line-height:1.9;font-family:Arial,sans-serif;">
    @if($sig->phone){{ e($sig->phone) }}@endif
    @if($sig->phone && ($sig->contact_email || $sig->website)) &nbsp;&bull;&nbsp; @endif
    @if($sig->contact_email){{ e($sig->contact_email) }}@endif
    @if($sig->contact_email && $sig->website) &nbsp;&bull;&nbsp; @endif
    @if($sig->website)<a href="{{ e($sig->website) }}" style="color:{{ $accent }};text-decoration:none;">{{ e($sig->website) }}</a>@endif
    @if($sig->linkedin)
      <br><a href="{{ e($sig->linkedin) }}" style="color:{{ $accent }};text-decoration:none;">LinkedIn</a>
    @endif
  </div>
</td></tr>
</table>
