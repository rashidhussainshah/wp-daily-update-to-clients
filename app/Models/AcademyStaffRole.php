<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyStaffRole extends Model
{
    use HasFactory;

    const CAPABILITY_INSTRUCTOR = 'instructor';
    const CAPABILITY_REVIEWER = 'reviewer';

    protected $fillable = ['user_id', 'capability'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeInstructors($query)
    {
        return $query->where('capability', self::CAPABILITY_INSTRUCTOR);
    }

    public function scopeReviewers($query)
    {
        return $query->where('capability', self::CAPABILITY_REVIEWER);
    }
}
