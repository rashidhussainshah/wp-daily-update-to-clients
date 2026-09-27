<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyAiReview extends Model
{
    use HasFactory;

    const VERDICT_APPROVE = 'approve';
    const VERDICT_NEEDS_WORK = 'needs_work';
    const VERDICT_REJECT = 'reject';

    const REVIEWER_STATUS_PENDING = 'pending';
    const REVIEWER_STATUS_APPROVED = 'approved';
    const REVIEWER_STATUS_SENT_BACK = 'sent_back';

    protected $fillable = [
        'enrollment_id',
        'stage_index',
        'project_title',
        'submission_link',
        'notes',
        'ai_score',
        'ai_verdict',
        'ai_feedback',
        'reviewer_id',
        'reviewer_status',
        'reviewer_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'ai_score' => 'decimal:1',
        'reviewed_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(DeveloperAcademyEnrollment::class, 'enrollment_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function scopePending($query)
    {
        return $query->where('reviewer_status', self::REVIEWER_STATUS_PENDING);
    }
}
