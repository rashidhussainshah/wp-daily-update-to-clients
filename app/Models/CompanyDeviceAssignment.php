<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyDeviceAssignment extends Model
{
    protected $fillable = [
        'device_id', 'user_id', 'assigned_by', 'assigned_on', 'returned_on',
        'condition_on_assign', 'condition_on_return', 'handover_notes', 'document',
    ];

    protected $casts = [
        'assigned_on' => 'date',
        'returned_on' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $assignment) {
            if (empty($assignment->assigned_by) && auth()->check()) {
                $assignment->assigned_by = auth()->id();
            }
        });

        $sync = fn (self $assignment) => optional($assignment->device)->syncCurrentAssignment();

        static::saved($sync);
        static::deleted($sync);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(CompanyDevice::class, 'device_id');
    }

    /** The user the device is / was handed to. */
    public function holder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Admin / HR user who recorded the handover. */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function getIsOpenAttribute(): bool
    {
        return is_null($this->returned_on);
    }
}
