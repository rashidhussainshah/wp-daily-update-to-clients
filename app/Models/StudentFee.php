<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFee extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['batch', 'date', 'student_id', 'receiver_id', 'amount', 'status'];
    // Scope to get data for the current month
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

    // Scope to get data where status is pending
    public function scopePendingStatus($query)
    {
        return $query->where('status', 'pending');
    }

    // Combined scope to get data for the current month and where status is pending
    // Combined scope to get data for the current month or where status is pending
    public function scopeCurrentMonthOrPendingStatus($query)
    {
        return $query->where(function ($query) {
            $query->currentMonth()
                ->orWhere(function ($query) {
                    $query->pendingStatus();
                });
        });
    }
}
