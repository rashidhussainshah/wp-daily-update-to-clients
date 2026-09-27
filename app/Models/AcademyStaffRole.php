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
    const CAPABILITY_ACCOUNTANT = 'accountant';
    const CAPABILITY_MARKETING = 'marketing';
    const CAPABILITY_PRINTER = 'printer';
    const CAPABILITY_CARD_MANAGER = 'card_manager';

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

    public function scopeAccountants($query)
    {
        return $query->where('capability', self::CAPABILITY_ACCOUNTANT);
    }

    public function scopeMarketingStaff($query)
    {
        return $query->where('capability', self::CAPABILITY_MARKETING);
    }

    public function scopePrinters($query)
    {
        return $query->where('capability', self::CAPABILITY_PRINTER);
    }

    public function scopeCardManagers($query)
    {
        return $query->where('capability', self::CAPABILITY_CARD_MANAGER);
    }
}
