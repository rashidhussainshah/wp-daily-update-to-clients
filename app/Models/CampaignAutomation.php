<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignAutomation extends Model
{
    protected $fillable = [
        'name', 'campaign_id', 'status', 'frequency',
        'send_window_start', 'send_window_end',
        'send_day_of_week', 'send_day_of_month', 'start_date', 'end_date',
        'batch_size', 'resend_gap_days', 'email_delay_seconds', 'emails_sent_in_batch',
        'target_role', 'emails_sent_total', 'last_run_at', 'next_run_at', 'queued_at', 'created_by',
        'skip_weekends', 'daily_send_cap', 'notify_email',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'last_run_at'   => 'datetime',
        'next_run_at'   => 'datetime',
        'queued_at'     => 'datetime',
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

    /**
     * Human-readable "what's actually happening right now" — surfaces queue
     * state that status/next_run_at alone don't show (e.g. "due but the
     * scheduler hasn't picked it up yet" vs "already queued and sending").
     */
    public function getRuntimeStatusAttribute(): array
    {
        if ($this->status === 'paused') {
            return ['text' => 'Paused', 'badge' => 'warning'];
        }

        if ($this->status === 'cancelled') {
            return ['text' => 'Cancelled', 'badge' => 'danger'];
        }

        // Checked before 'completed': for frequency=once, status flips to
        // completed at dispatch time, before the job has actually run — so
        // without this ordering, a still-sending batch would misreport as done.
        if ($this->queued_at) {
            return ['text' => 'Sending now (queued ' . $this->queued_at->diffForHumans() . ')', 'badge' => 'info'];
        }

        if ($this->status === 'completed') {
            return ['text' => 'Completed', 'badge' => 'default'];
        }

        if ($this->next_run_at && $this->next_run_at->isFuture()) {
            return ['text' => 'Scheduled for ' . $this->next_run_at->format('M j, g:i A'), 'badge' => 'primary'];
        }

        if ($this->next_run_at && $this->next_run_at->isPast()) {
            return ['text' => 'Due — waiting for next scheduler run', 'badge' => 'warning'];
        }

        return ['text' => '—', 'badge' => 'default'];
    }
}
