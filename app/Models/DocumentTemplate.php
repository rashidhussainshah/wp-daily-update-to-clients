<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A reusable letter template (experience letter, relieving letter,
 * internship letter, or anything HR invents later) - `category` is free
 * text on purpose, not an enum, so HR can create a brand-new letter type
 * from the builder UI with zero code changes. `body` is raw HTML with
 * {{token}} placeholders; DocumentTemplateService resolves them against a
 * real employee (or sample data) at preview/issue time.
 */
class DocumentTemplate extends Model
{
    use HasFactory;

    const DESIGN_CLASSIC = 'classic';
    const DESIGN_MODERN = 'modern';
    const DESIGN_MINIMAL = 'minimal';

    /** @return array<string, string> design key => display label, for dropdowns */
    public static function designs(): array
    {
        return [
            self::DESIGN_CLASSIC => 'Classic (green double border, formal)',
            self::DESIGN_MODERN => 'Modern (green header band)',
            self::DESIGN_MINIMAL => 'Minimal (no borders, plain letter)',
        ];
    }

    protected $fillable = [
        'name',
        'category',
        'design',
        'body',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function issuances(): HasMany
    {
        return $this->hasMany(DocumentIssuance::class, 'template_id');
    }
}
