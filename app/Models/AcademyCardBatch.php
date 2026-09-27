<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademyCardBatch extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_READY = 'ready';
    const STATUS_PRINTED = 'printed';

    protected $fillable = ['label', 'status', 'created_by', 'printed_by', 'ready_at', 'printed_at', 'notes'];

    protected $casts = [
        'ready_at' => 'datetime',
        'printed_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AcademyCardBatchItem::class, 'batch_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'academy_card_batch_items', 'batch_id', 'user_id')
            ->withPivot(['printed', 'printed_at'])
            ->withTimestamps();
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeReady($query)
    {
        return $query->where('status', self::STATUS_READY);
    }
}
