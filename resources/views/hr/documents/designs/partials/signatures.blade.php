{{-- Two independent signature slots - each is either a typed name (rendered
     as text, underlined by the border-top rule) or an uploaded signature
     image with the title still printed as text underneath. Shared across
     all three designs so the image-vs-text fallback logic lives in one
     place. Expects $signatories (from DocumentTemplateService::signatories())
     and CSS classes .sig-col / .sig-name / .sig-title already defined by
     the including design. --}}
@foreach($signatories as $sig)
    <div class="sig-col">
        @if($sig['image_url'])
            <img src="{{ $sig['image_url'] }}" class="sig-image" alt="Signature">
        @else
            <div class="sig-name">{{ $sig['name'] }}</div>
        @endif
        <div class="sig-title">{{ strtoupper($sig['title']) }}</div>
    </div>
@endforeach
