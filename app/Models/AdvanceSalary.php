<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceSalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'month',
        'reason',
        'status',
        'deducted_date',
    ];

    protected $dates = [
        'deleted_at',
        'deducted_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the advance salary
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
