<?php

namespace App\Traits;

trait FilterByDate
{
    public function scopeWhereDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween($this->getTable() . '.' . $this->getCreatedAtColumn(), [$startDate, $endDate]);
    }

    public function scopeWhereDateIs($query, $date)
    {
        return $query->where($this->getTable() . '.' . $this->getCreatedAtColumn(), $date);
    }
}
