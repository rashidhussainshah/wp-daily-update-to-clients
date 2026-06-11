<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $fillable = ['date', 'type', 'amount', 'category', 'description', 'recorded_by'];

    protected $casts = ['date' => 'date'];

    public static array $categories = [
        'lunch'           => 'Weekly Lunch',
        'drinks'          => 'Cold Drinks / Refreshments',
        'guest'           => 'Guest Expenses',
        'office_supplies' => 'Office Supplies / Cleaning',
        'travel'          => 'Travel / Petty Cash',
        'other'           => 'Other',
    ];

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
