@php
    // Quick date-range presets. Links keep every other active filter and swap
    // only the period; the form's own date inputs act as the "custom" range.
    $presets = [
        '' => 'All Time',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'last_2_months' => 'Last 2 Months',
        'last_6_months' => 'Last 6 Months',
        'last_year' => '1 Year',
    ];
    $baseQuery = collect(request()->query())->except(['period', 'date_from', 'date_to', 'page']);
    $activePeriod = request()->query('period', '');
    $hasCustomDates = !request()->query('period') && (request()->query('date_from') || request()->query('date_to'));
@endphp
<div style="margin:4px 4px 10px;">
    @foreach($presets as $key => $label)
        @php $query = $key === '' ? $baseQuery->all() : $baseQuery->merge(['period' => $key])->all(); @endphp
        <a href="{{ url()->current() . (count($query) ? '?' . http_build_query($query) : '') }}"
           class="btn btn-sm {{ $activePeriod === $key && !$hasCustomDates ? 'btn-primary' : 'btn-default' }}">{{ $label }}</a>
    @endforeach
    <span class="btn btn-sm {{ $hasCustomDates ? 'btn-primary' : 'btn-default' }}" style="cursor:default;" title="Use the from/to date pickers below">Custom</span>
    @if(($filters['date_from'] ?? null) || ($filters['date_to'] ?? null))
        <small class="text-muted" style="margin-left:8px;">Showing {{ $filters['date_from'] ?? 'start' }} &rarr; {{ $filters['date_to'] ?? 'today' }}</small>
    @endif
</div>
