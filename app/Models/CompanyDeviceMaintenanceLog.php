<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyDeviceMaintenanceLog extends Model
{
    protected $fillable = ['device_id', 'type', 'note', 'logged_by', 'logged_at'];

    protected $casts = [
        'logged_at' => 'date',
    ];

    public const TYPES = [
        'battery'    => 'Battery Replaced',
        'hard_drive' => 'Hard Drive / SSD Replaced',
        'screen'     => 'Screen Replaced',
        'keyboard'   => 'Keyboard Replaced',
        'ram'        => 'RAM Upgraded',
        'other'      => 'Other',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(CompanyDevice::class, 'device_id');
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
