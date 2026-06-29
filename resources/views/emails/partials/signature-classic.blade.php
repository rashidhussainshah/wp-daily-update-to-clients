@php $accent = e($sig->accent_color ?: '#1a1a2e'); @endphp
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;border-top:3px solid {{ $accent }};margin-top:28px;">
<tr><td style="padding-top:16px;">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  @if($sig->photo_url)
  <td style="width:76px;vertical-align:top;padding-right:16px;">
    <img src="{{ $sig->photo_url }}" width="64" height="64" alt="{{ e($sig->display_name) }}"
         style="border-radius:50%;display:block;width:64px;height:64px;object-fit:cover;border:2px solid {{ $accent }};">
  </td>
  @endif
  <td style="vertical-align:top;">
    @if($sig->handwritten_url)
      <img src="{{ $sig->handwritten_url }}" alt="signature" style="max-height:38px;display:block;margin-bottom:6px;">
    @endif
    <div style="font-size:15px;font-weight:700;color:{{ $accent }};line-height:1.2;font-family:Arial,sans-serif;">{{ e($sig->display_name) }}</div>
    @if($sig->designation)
      <div style="font-size:12px;color:#555555;margin-top:3px;font-family:Arial,sans-serif;">{{ e($sig->designation) }}</div>
    @endif
    @if($sig->tagline)
      <div style="font-size:11px;color:#999999;margin-top:2px;font-style:italic;font-family:Arial,sans-serif;">{{ e($sig->tagline) }}</div>
    @endif
    <table border="0" cellpadding="0" cellspacing="0" style="margin-top:9px;">
      @if($sig->phone)
      <tr><td style="font-size:12px;color:#666666;padding-bottom:2px;font-family:Arial,sans-serif;">
        <span style="color:{{ $accent }};">&#9990;</span>&nbsp;&nbsp;{{ e($sig->phone) }}
      </td></tr>
      @endif
      @if($sig->contact_email)
      <tr><td style="font-size:12px;color:#666666;padding-bottom:2px;font-family:Arial,sans-serif;">
        <span style="color:{{ $accent }};">&#9993;</span>&nbsp;&nbsp;{{ e($sig->contact_email) }}
      </td></tr>
      @endif
      @if($sig->website)
      <tr><td style="font-size:12px;padding-bottom:2px;font-family:Arial,sans-serif;">
        <span style="color:{{ $accent }};">&#127760;</span>&nbsp;&nbsp;<a href="{{ e($sig->website) }}" style="color:{{ $accent }};text-decoration:none;">{{ e($sig->website) }}</a>
      </td></tr>
      @endif
      @if($sig->linkedin)
      <tr><td style="font-size:12px;font-family:Arial,sans-serif;">
        <a href="{{ e($sig->linkedin) }}" style="color:{{ $accent }};text-decoration:none;font-weight:600;">LinkedIn Profile</a>
      </td></tr>
      @endif
    </table>
  </td>
</tr>
</table>
</td></tr>
</table>
