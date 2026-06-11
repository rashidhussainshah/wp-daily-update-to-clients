<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = [
        'name', 'registrar', 'expires_on',
        'renewal_cost_usd', 'renewal_cost_pkr',
        'project', 'paid_from', 'auto_renew', 'notes',
    ];

    protected $casts = [
        'expires_on'  => 'date',
        'auto_renew'  => 'boolean',
    ];

    public static array $bankAccounts = [
        'rashid_al_habib' => 'Rashid — Bank Al Habib',
        'rashid_meezan'   => 'Rashid — Meezan Bank',
        'zahid_allied'    => 'Zahid — Allied Bank',
        'cash'            => 'Cash',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(MonthlyExpense::class);
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->expires_on, false);
    }

    public function getExpiryStatusAttribute(): string
    {
        $days = $this->days_until_expiry;
        if ($days < 0)   return 'expired';
        if ($days <= 14) return 'critical';
        if ($days <= 30) return 'warning';
        return 'ok';
    }
}
