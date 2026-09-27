<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DeveloperAcademyEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_WITHDRAWN = 'withdrawn';

    protected $fillable = [
        'user_id',
        'track_id',
        'instructor_id',
        'current_stage',
        'progress',
        'badges_earned',
        'status',
        'parent_view_token',
        'enrolled_at',
    ];

    protected $casts = [
        'progress' => 'array',
        'badges_earned' => 'array',
        'enrolled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $enrollment) {
            $enrollment->enrolled_at ??= now();
            $enrollment->parent_view_token ??= Str::random(48);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(AcademyTrack::class, 'track_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function aiReviews(): HasMany
    {
        return $this->hasMany(AcademyAiReview::class, 'enrollment_id');
    }

    public function feeInvoices(): HasMany
    {
        return $this->hasMany(AcademyFeeInvoice::class, 'enrollment_id');
    }

    public function certificate(): HasMany
    {
        return $this->hasMany(AcademyCertificate::class, 'enrollment_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Overall completion % across all stages - computed, never stored/typed
     * in directly (per A2's plan).
     */
    public function progressPercent(): int
    {
        $stages = $this->track?->stages() ?? [];
        $totalStages = count($stages);
        if ($totalStages === 0) {
            return 0;
        }

        $completedStages = $this->current_stage;
        $currentStageWeight = 0;

        if (isset($stages[$this->current_stage])) {
            $skills = $stages[$this->current_stage]['skills'] ?? [];
            $projects = $stages[$this->current_stage]['projects'] ?? [];
            $items = count($skills) + count($projects);

            if ($items > 0) {
                $checked = collect($this->progress[$this->current_stage]['skills'] ?? [])->filter()->count()
                    + collect($this->progress[$this->current_stage]['projects'] ?? [])
                        ->filter(fn ($status) => $status === 'done')->count();
                $currentStageWeight = $checked / $items;
            }
        }

        return (int) round((($completedStages + $currentStageWeight) / $totalStages) * 100);
    }

    /**
     * Motivational level badge for this enrollment - Fiverr-style tiers
     * (New Talent -> ... -> Certified Professional), computed purely from
     * progressPercent() and status so there's no new data to maintain.
     *
     * Deliberately a plain method (not a stored column) so it's cheap to
     * reuse anywhere this enrollment is already loaded - the student
     * dashboard today, and later the public developer-hire/marketing
     * profile page without any schema change.
     */
    public function levelTier(): array
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return ['tier' => 5, 'key' => 'certified', 'label' => 'Certified Professional', 'icon' => '🏆'];
        }

        $percent = $this->progressPercent();

        return match (true) {
            $percent >= 75 => ['tier' => 4, 'key' => 'advanced', 'label' => 'Advanced Talent', 'icon' => '🏅'],
            $percent >= 50 => ['tier' => 3, 'key' => 'skilled', 'label' => 'Skilled Talent', 'icon' => '⭐'],
            $percent >= 25 => ['tier' => 2, 'key' => 'developing', 'label' => 'Developing Talent', 'icon' => '🚀'],
            default => ['tier' => 1, 'key' => 'new', 'label' => 'New Talent', 'icon' => '🌱'],
        };
    }
}
