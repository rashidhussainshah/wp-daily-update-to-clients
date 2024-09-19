<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RykStudentFee extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['batch', 'date', 'student_id', 'receiver_id', 'amount', 'status', 'notes'];
    // Scope to get data for the current month
    // Scope to get data for the current month
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

    // Scope to get data where status is Paid
    public function scopePaidStatus($query)
    {
        return $query->where('status', 'paid');
    }

    // Scope to get data where status is Pending
    public function scopePendingStatus($query)
    {
        return $query->where('status', 'pending');
    }

    // Combined scope to get data for the current month without Paid status or where status is Pending
    public function scopeCurrentMonthOrPendingStatus($query)
    {
        if (Auth::user()->role && (Auth::user()->role->id == setting('academy.ryk_student_role_id'))) {
            return $query->where('student_id', Auth::user()->id)->where(function ($query) {
                $query->currentMonth()
                    ->whereNotIn('status', ['paid'])
                    ->orWhere(function ($query) {
                        $query->pendingStatus();
                    });
            });
        }else {
            return $query->where(function ($query) {
                $query->currentMonth()
                    ->whereNotIn('status', ['paid'])
                    ->orWhere(function ($query) {
                        $query->pendingStatus();
                    });
            });
        }
    }
    // Define the belongsTo relationship with User model
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
