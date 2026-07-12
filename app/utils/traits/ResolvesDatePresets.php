<?php

namespace App\utils\traits;

trait ResolvesDatePresets
{
    /**
     * Resolve a date-range preset (period=) into date_from/date_to filters.
     * 'custom' (or no period) leaves the user's own dates untouched.
     */
    protected function resolveDateFilters(array $filters): array
    {
        $ranges = [
            'this_month' => [now()->startOfMonth(), now()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'last_2_months' => [now()->subMonthsNoOverflow(2)->startOfMonth(), now()],
            'last_6_months' => [now()->subMonthsNoOverflow(6)->startOfMonth(), now()],
            'last_year' => [now()->subYear()->startOfDay(), now()],
        ];

        $period = $filters['period'] ?? null;
        if ($period && isset($ranges[$period])) {
            $filters['date_from'] = $ranges[$period][0]->toDateString();
            $filters['date_to'] = $ranges[$period][1]->toDateString();
        }

        return $filters;
    }
}
