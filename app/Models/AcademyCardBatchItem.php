<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyCardBatchItem extends Model
{
    protected $fillable = ['batch_id', 'user_id', 'printed', 'printed_at'];

    protected $casts = [
        'printed' => 'boolean',
        'printed_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(AcademyCardBatch::class, 'batch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
