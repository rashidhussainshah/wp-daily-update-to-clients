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

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'course_id',
        'type',
        'title',
        'recipient_name',
        'verify_code',
        'pdf_path',
        'issued_by',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
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
}
