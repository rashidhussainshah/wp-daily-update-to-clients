<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyExpense extends Model
{
    protected $fillable = [
        'month', 'category', 'amount_pkr', 'expected_amount_pkr', 'parent_expense_id', 'is_fixed',
        'is_advance', 'paid_from', 'note', 'attachments', 'domain_id', 'created_by',
    ];

    protected $casts = [
        'is_fixed'   => 'boolean',
        'is_advance' => 'boolean',
        'attachments' => 'array',
    ];

    public static array $categories = [
        'rent'            => 'Office Rent',
        'electricity'     => 'Electricity Bill',
        'water'           => 'Water Bill',
        'lunch'           => 'Lunch / Food',
        'cleaning'        => 'Office Cleaning & Supplies',
        'guest'           => 'Guest Expenses',
        'claude_accounts' => 'Claude AI Accounts',
        'internet_moon'   => 'Internet — Moon',
        'internet_prime'  => 'Internet — Prime',
        'domain_renewal'  => 'Domain Renewal',
        'hosting'         => 'Hosting / Servers',
        'other'           => 'Other',
    ];

    public static array $bankAccounts = [
        'rashid_al_habib' => 'Rashid — Bank Al Habib',
        'rashid_meezan'   => 'Rashid — Meezan Bank',
        'zahid_allied'    => 'Zahid — Allied Bank',
        'cash'            => 'Cash',
    ];

    // Fixed expenses auto-filled each month
    public static array $fixedDefaults = [
        ['category' => 'rent',            'amount_pkr' => 16500,  'paid_from' => 'rashid_al_habib', 'note' => 'Office rent'],
        ['category' => 'claude_accounts', 'amount_pkr' => 12000,  'paid_from' => 'rashid_meezan',   'note' => '2 × Claude Pro accounts'],
        ['category' => 'internet_moon',   'amount_pkr' => 6000,   'paid_from' => 'rashid_al_habib', 'note' => 'Moon Internet'],
        ['category' => 'internet_prime',  'amount_pkr' => 1900,   'paid_from' => 'rashid_al_habib', 'note' => 'Prime connection'],
    ];

    /**
     * Create this month's fixed expenses (rent, internet, etc.) if not already added.
     * Used by both the "Auto-fill Fixed" button and the monthly scheduled job.
     */
    public static function seedFixedDefaultsForMonth(?string $month = null, ?int $userId = null): int
    {
        $month ??= now()->format('Y-m');
        $added = 0;

        foreach (self::$fixedDefaults as $row) {
            $exists = self::where('month', $month)->where('category', $row['category'])->where('is_fixed', true)->exists();
            if (!$exists) {
                self::create(array_merge($row, ['month' => $month, 'is_fixed' => true, 'created_by' => $userId]));
                $added++;
            }
        }

        return $added;
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A "bill" (expected_amount_pkr set) can have many partial payments against it.
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_expense_id')->orderBy('created_at');
    }

    public function parentExpense(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_expense_id');
    }

    public function isBill(): bool
    {
        return !is_null($this->expected_amount_pkr);
    }

    public function getPaidAmountAttribute(): float
    {
        return $this->isBill() ? (float) $this->payments()->sum('amount_pkr') : (float) $this->amount_pkr;
    }

    public function getPendingAmountAttribute(): ?float
    {
        return $this->isBill() ? max(0, (float) $this->expected_amount_pkr - $this->paid_amount) : null;
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::$categories[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getBankLabelAttribute(): string
    {
        return self::$bankAccounts[$this->paid_from] ?? ($this->paid_from ?? '—');
    }
}
