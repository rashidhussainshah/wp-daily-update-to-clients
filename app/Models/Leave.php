<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    const MANAGEMENT_APPROVAL_PENDING  = 'pending';
    const MANAGEMENT_APPROVAL_APPROVED = 'approved';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'coo_required',
        'coo_approved_at',
        'coo_approved_by',
        'management_approval',
    ];

    protected $dates = ['deleted_at', 'start_date', 'end_date', 'coo_approved_at'];

    protected $casts = [
        'management_approval' => 'string',
    ];

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

    public function setUserIdAttribute($value)
    {
        // Only set from Auth if value is not provided
        if (!$value && Auth::check()) {
            $this->attributes['user_id'] = Auth::user()->id;
        } else {
            $this->attributes['user_id'] = $value;
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
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
