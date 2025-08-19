<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        static::saving(function ($leave) {
            // Ensure user_id is set
            if (Auth::check() && empty($leave->user_id)) {
                $leave->user_id = Auth::id();
            }
            // Auto-flag COO requirement if end_date makes it > 2 days (or setting value if available)
            try {
                $maxConsecutiveDays = (int) (function_exists('setting') ? setting('leaves.total_consecutive_days_leaves', 2) : 2);
            } catch (\Throwable $e) {
                $maxConsecutiveDays = 2;
            }
            if (!empty($leave->start_date)) {
                $start = \Carbon\Carbon::parse($leave->start_date)->startOfDay();
                $end = !empty($leave->end_date) ? \Carbon\Carbon::parse($leave->end_date)->startOfDay() : $start->copy();
                $days = $start->diffInDays($end) + 1;
                $leave->coo_required = $days > $maxConsecutiveDays;
            }
        });
    }

    public function setUserIdAttribute()
    {
        $this->attributes['user_id'] = Auth::user()->id;
    }
    public function scopeCurrentUser($query)
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::ADMINISTRATOR_ROLE_NAME )) {
            return $query;
        } else {
            return $query->where('user_id', Auth::user()->id);
        }
    }
}
