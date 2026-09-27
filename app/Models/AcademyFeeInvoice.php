<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyFeeInvoice extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';

    protected $fillable = [
        'enrollment_id',
        'month',
        'registration_fee_amount',
        'monthly_fee_amount',
        'total_amount',
        'status',
        'payment_proof_path',
        'payment_proof_submitted_at',
        'paid_at',
        'marked_paid_by',
        'instructor_commission_amount',
        'instructor_commission_credited',
        'instructor_paid_at',
        'instructor_paid_by',
        'instructor_payout_proof_path',
    ];

    protected $casts = [
        'month' => 'date',
        'registration_fee_amount' => 'decimal:2',
        'monthly_fee_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'instructor_commission_amount' => 'decimal:2',
        'instructor_commission_credited' => 'boolean',
        'paid_at' => 'datetime',
        'payment_proof_submitted_at' => 'datetime',
        'instructor_paid_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(DeveloperAcademyEnrollment::class, 'enrollment_id');
    }

    public function markedPaidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_paid_by');
    }

    public function instructorPaidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_paid_by');
    }

    /**
     * Commission "credited" (calculated, attributed) is not the same as
     * the instructor actually having been paid it - this is that second,
     * separate step. Administrator-only (see AcademyInstructorPayoutController).
     */
    public function markInstructorPaid(User $paidBy, ?string $proofPath = null): void
    {
        if ($this->instructor_paid_at) {
            return; // already paid out - never double-pay
        }

        $this->update([
            'instructor_paid_at' => now(),
            'instructor_paid_by' => $paidBy->id,
            'instructor_payout_proof_path' => $proofPath,
        ]);
    }

    /**
     * Commission owed but not yet actually paid to the instructor.
     */
    public function scopePendingInstructorPayout($query)
    {
        return $query->where('instructor_commission_credited', true)->whereNull('instructor_paid_at');
    }

    /**
     * The single trigger point for this whole billing flow (A6): marking an
     * invoice paid is what credits the instructor's commission - nothing
     * before this step pays anyone anything. Role-gating (HR/Fee Admin or
     * Administrator) belongs in the controller, not here.
     */
    public function markPaid(User $markedBy): void
    {
        if ($this->status === self::STATUS_PAID) {
            return; // already paid - never double-credit
        }

        $enrollment = $this->enrollment;
        $rate = $enrollment->track->instructorCommissionRate();
        $commission = $enrollment->instructor_id
            ? round((float) $this->monthly_fee_amount * $rate, 2)
            : null;

        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'marked_paid_by' => $markedBy->id,
            'instructor_commission_amount' => $commission,
            'instructor_commission_credited' => $commission !== null,
        ]);
    }
}
