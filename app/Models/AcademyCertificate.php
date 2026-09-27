<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AcademyCertificate extends Model
{
    use HasFactory;

    const TYPE_TRACK = 'track';
    const TYPE_COURSE = 'course';

    const DESIGN_CLASSIC = 'classic';
    const DESIGN_LINKEDIN = 'linkedin';
    const DESIGN_UDEMY = 'udemy';

    /** @return array<string, string> design key => display label, for dropdowns */
    public static function designs(): array
    {
        return [
            self::DESIGN_CLASSIC => 'Classic (green, formal)',
            self::DESIGN_LINKEDIN => 'LinkedIn-style (blue, badge)',
            self::DESIGN_UDEMY => 'Udemy-style (bold banner)',
        ];
    }

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'course_id',
        'type',
        'design',
        'title',
        'achievement_note',
        'is_staff_certificate',
        'recipient_name',
        'verify_code',
        'pdf_path',
        'issued_by',
        'issued_at',
        'posted_at',
        'posted_by',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'posted_at' => 'datetime',
        'is_staff_certificate' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (self $certificate) {
            $certificate->verify_code ??= 'WP-ACAD-' . now()->format('Y') . '-' . strtoupper(Str::random(8));
            $certificate->issued_at ??= now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(DeveloperAcademyEnrollment::class, 'enrollment_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(AcademyCourse::class, 'course_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
