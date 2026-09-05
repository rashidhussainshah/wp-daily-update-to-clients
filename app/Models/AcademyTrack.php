<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademyTrack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'designation',
        'description',
        'curriculum',
        'is_open_for_enrollment',
        'registration_fee_enabled',
        'registration_fee_amount',
        'monthly_fee_amount',
        'instructor_commission_percent',
        'default_instructor_id',
    ];

    protected $casts = [
        'curriculum' => 'array',
        'is_open_for_enrollment' => 'boolean',
        'registration_fee_enabled' => 'boolean',
        'registration_fee_amount' => 'decimal:2',
        'monthly_fee_amount' => 'decimal:2',
        'instructor_commission_percent' => 'decimal:2',
    ];

    public function defaultInstructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(DeveloperAcademyEnrollment::class, 'track_id');
    }

    /**
     * The stage definitions from the curriculum JSON, indexed from 0.
     */
    public function stages(): array
    {
        return $this->curriculum['stages'] ?? [];
    }

    /**
     * Effective instructor commission rate, falling back to the global
     * setting when this track hasn't overridden it - see A6.
     */
    public function instructorCommissionRate(): float
    {
        $percent = $this->instructor_commission_percent
            ?? setting('academy.default_instructor_commission_percent', 30);

        return ((float) $percent) / 100;
    }
}
