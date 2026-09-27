<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DocumentIssuance extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
        'recipient_name',
        'recipient_email',
        'issued_by',
        'data',
        'pdf_path',
        'verify_code',
        'issued_at',
    ];

    protected $casts = [
        'data' => 'array',
        'issued_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $issuance) {
            $issuance->verify_code ??= 'WP-DOC-' . now()->format('Y') . '-' . strtoupper(Str::random(8));
            $issuance->issued_at ??= now();
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class, 'template_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
