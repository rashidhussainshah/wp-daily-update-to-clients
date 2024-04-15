@php
$dimmerGroups = Voyager::dimmers();
@endphp

@if ($dimmerGroups)
    @foreach($dimmerGroups as $dimmerGroup)
        @if ($dimmerGroup && is_object($dimmerGroup) && $dimmerGroup->any() && $dimmerGroup->model() && isset($dimmerGroup->name))
            @php
            $count = $dimmerGroup->count();
            $classes = [
                'col-xs-12',
                'col-sm-'.($count >= 2 ? '6' : '12'),
                'col-md-'.($count >= 3 ? '4' : ($count >= 2 ? '6' : '12')),
            ];
            $class = implode(' ', $classes);
            $prefix = "<div class='{$class}'>";
            $suffix = '</div>';
            @endphp
            <div class="clearfix container-fluid row">
                {!! $prefix.$dimmerGroup->setSeparator($suffix.$prefix)->display().$suffix !!}
            </div>
        @elseif (!$dimmerGroup)
            {{-- Optional: Log the error for further investigation --}}
            @php
            \Log::error("Error displaying dimmer group. Dimmer group is null.");
            @endphp
        @else
            {{-- Optional: Log the error for further investigation --}}
            @php
            \Log::error("Error displaying dimmer group. Dimmer group: " . json_encode($dimmerGroup));
            @endphp
        @endif
    @endforeach
@endif
