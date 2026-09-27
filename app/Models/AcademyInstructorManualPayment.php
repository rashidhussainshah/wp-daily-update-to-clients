<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A payment to an instructor NOT tied to a specific student's fee invoice -
 * an advance against future commission, a bonus, or a manual adjustment.
 * See academy_fee_invoices for the normal per-student commission payout.
 */
class AcademyInstructorManualPayment extends Model
{
    const TYPE_ADVANCE = 'advance';
    const TYPE_BONUS = 'bonus';
    const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'instructor_id',
        'amount',
        'type',
        'note',
        'proof_path',
        'paid_by',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $payment) {
            $payment->paid_at ??= now();
        });
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
