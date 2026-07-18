<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPaymentLog extends Model
{
    protected $fillable = ['user_payment_id', 'actor_id', 'action', 'changes'];

    protected $casts = [
        'changes' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function userPayment(): BelongsTo
    {
        return $this->belongsTo(UserPayment::class);
    }
}
