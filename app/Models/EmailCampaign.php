<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    protected $fillable = [
        'name', 'subject', 'html_body', 'text_body',
        'from_name', 'from_email', 'target_role', 'smtp_account_id',
        'status', 'created_by', 'scheduled_at', 'sent_at',
        'sent_count', 'failed_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function smtpAccount(): BelongsTo
    {
        return $this->belongsTo(SmtpAccount::class, 'smtp_account_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EmailCampaignLog::class, 'campaign_id');
    }

    public function pendingLogs(): HasMany
    {
        return $this->logs()->where('status', 'pending');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }
}
