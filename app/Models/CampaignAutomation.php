<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignAutomation extends Model
{
    protected $fillable = [
        'name', 'campaign_id', 'status', 'frequency', 'send_time',
        'send_window_start', 'send_window_end',
        'send_day_of_week', 'send_day_of_month', 'start_date', 'end_date',
        'batch_size', 'resend_gap_days', 'email_delay_seconds', 'emails_sent_in_batch',
        'target_role', 'emails_sent_total', 'last_run_at', 'next_run_at', 'created_by',
        'skip_weekends', 'daily_send_cap', 'notify_email',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'last_run_at'   => 'datetime',
        'next_run_at'   => 'datetime',
        'skip_weekends' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CampaignAutomationLog::class, 'automation_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'success',
            'paused'    => 'warning',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'once'    => 'Once',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly on ' . ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$this->send_day_of_week ?? 0],
            'monthly' => 'Monthly on day ' . ($this->send_day_of_month ?? 1),
            default   => $this->frequency,
        };
    }
}
