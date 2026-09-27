<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyDevice extends Model
{
    protected $fillable = [
        'asset_tag', 'name', 'type', 'brand', 'model', 'serial_number',
        'specs', 'condition', 'purchase_date', 'purchase_cost', 'currency',
        'vendor', 'invoice', 'attachments', 'status', 'assigned_to',
        'assigned_at', 'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'assigned_at'   => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public const TYPES = [
        'laptop'    => 'Laptop',
        'desktop'   => 'Desktop',
        'phone'     => 'Phone',
        'tablet'    => 'Tablet',
        'monitor'   => 'Monitor',
        'accessory' => 'Accessory',
        'other'     => 'Other',
    ];

    public const CONDITIONS = [
        'new'  => 'New',
        'good' => 'Good',
        'fair' => 'Fair',
        'poor' => 'Poor',
    ];

    public const STATUSES = [
        'available' => 'Available',
        'assigned'  => 'Assigned',
        'repair'    => 'In Repair',
        'retired'   => 'Retired',
        'lost'      => 'Lost / Stolen',
    ];

    /** Current holder (denormalised from the open assignment row). */
    public function holder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** Full assignment history, newest first. */
    public function history(): HasMany
    {
        return $this->hasMany(CompanyDeviceAssignment::class, 'device_id')->orderByDesc('assigned_on');
    }

    /** Quick maintenance / repair log (battery, hard drive, etc.), newest first. */
    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(CompanyDeviceMaintenanceLog::class, 'device_id')
            ->orderByDesc('logged_at')
            ->orderByDesc('id');
    }

    /**
     * Recalculate assigned_to / assigned_at / status from the open assignment
     * (returned_on IS NULL). Called by CompanyDeviceAssignment model events so
     * the device row always reflects reality without manual bookkeeping.
     * A manual repair / retired / lost status is never overwritten.
     */
    public function syncCurrentAssignment(): void
    {
        $open = $this->history()->whereNull('returned_on')->first();

        if ($open) {
            $this->forceFill([
                'assigned_to' => $open->user_id,
                'assigned_at' => $open->assigned_on,
                'status'      => in_array($this->status, ['repair', 'retired', 'lost'], true)
                    ? $this->status
                    : 'assigned',
            ])->saveQuietly();

            return;
        }

        $this->forceFill([
            'assigned_to' => null,
            'assigned_at' => null,
            'status'      => $this->status === 'assigned' ? 'available' : $this->status,
        ])->saveQuietly();
    }
}
