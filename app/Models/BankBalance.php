<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankBalance extends Model
{
    protected $fillable = ['month', 'recorded_on', 'account', 'balance_pkr', 'note', 'recorded_by'];

    protected $casts = ['recorded_on' => 'date'];

    public static array $accounts = [
        'rashid_al_habib' => 'Rashid — Bank Al Habib',
        'rashid_meezan'   => 'Rashid — Meezan Bank',
        'zahid_allied'    => 'Zahid — Allied Bank',
        'cash'            => 'Cash (Office)',
    ];

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
