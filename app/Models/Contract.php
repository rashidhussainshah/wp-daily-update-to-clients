<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'start_date',
        'end_date',
        'currency',
        'status',
        'contract_detail',
        'attachments',
        'monthly_salary',
        'bank_detail',
        'bank_detail_attachments',
    ];

    protected $dates = ['deleted_at', 'start_date', 'end_date'];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
    ];

    /**
     * Get the user that owns the contract
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate daily salary based on monthly salary
     */
    public function getDailySalaryAttribute()
    {
        if (!$this->monthly_salary) {
            return 0;
        }
        return round($this->monthly_salary / 30, 2);
    }
}
