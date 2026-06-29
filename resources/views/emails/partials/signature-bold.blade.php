@php $accent = e($sig->accent_color ?: '#1a1a2e'); @endphp
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;margin-top:28px;border-top:3px solid {{ $accent }};">
<tr><td style="padding-top:14px;">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td style="width:5px;background:{{ $accent }};border-radius:3px;">&nbsp;</td>

  @if($sig->photo_url)
  <td style="width:76px;vertical-align:top;padding:0 14px;">
    <img src="{{ $sig->photo_url }}" width="60" height="60" alt="{{ e($sig->display_name) }}"
         style="border-radius:6px;display:block;width:60px;height:60px;object-fit:cover;">
  </td>
  @else
  <td style="width:12px;"></td>
  @endif

  <td style="vertical-align:top;padding-left:{{ $sig->photo_url ? '0' : '8' }}px;">
    @if($sig->handwritten_url)
      <img src="{{ $sig->handwritten_url }}" alt="signature" style="max-height:36px;display:block;margin-bottom:5px;">
    @endif
    <div style="font-size:16px;font-weight:800;color:{{ $accent }};line-height:1.2;font-family:Arial,sans-serif;">{{ e($sig->display_name) }}</div>
    @if($sig->designation)
      <div style="font-size:12px;color:#444444;font-weight:600;margin-top:2px;font-family:Arial,sans-serif;">{{ e($sig->designation) }}</div>
    @endif
    @if($sig->tagline)
      <div style="font-size:11px;color:#999999;margin-top:2px;font-style:italic;font-family:Arial,sans-serif;">{{ e($sig->tagline) }}</div>
    @endif
    <div style="margin-top:8px;font-size:12px;color:#666666;line-height:1.8;font-family:Arial,sans-serif;">
      @if($sig->phone)
        {{ e($sig->phone) }}
        @if($sig->contact_email || $sig->website) &nbsp;|&nbsp; @endif
      @endif
      @if($sig->contact_email)
        {{ e($sig->contact_email) }}
        @if($sig->website) &nbsp;|&nbsp; @endif
      @endif
      @if($sig->website)
        <a href="{{ e($sig->website) }}" style="color:{{ $accent }};text-decoration:none;font-weight:600;">{{ e($sig->website) }}</a>
      @endif
      @if($sig->linkedin)
        <br><a href="{{ e($sig->linkedin) }}" style="color:{{ $accent }};text-decoration:none;">LinkedIn</a>
      @endif
    </div>
  </td>
</tr>
</table>
</td></tr>
</table>
