<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Income extends Model
{
    use HasFactory, SoftDeletes;
    public $allow_export_all = true;

    /**
     * Accessors Voyager may use as relationship labels (see the
     * user-payments income dropdown, which shows dropdown_label).
     */
    public $additional_attributes = ['dropdown_label'];

    /**
     * Maps income sources to the client_source values used on payment
     * requests (user_payments keeps the historic 'payonner' spelling).
     */
    const CLIENT_SOURCE_MAP = [
        'payoneer' => 'payonner',
        'student_fee' => 'other',
    ];

    protected static function booted()
    {
        static::saving(function (Income $income) {
            // Converted PKR is tracked automatically: amount x conversion rate.
            // Historic rows without a conversion_rate keep their manual value.
            // Note: amount_in only controls this tracking calculation - the
            // payment-request flow (PaymentCalculationService, store()) always
            // treats total_earning as USD and never reads amount_in, so a
            // PKR-denominated income does not get converted differently there.
            if ($income->amount && $income->conversion_rate
                && strtolower($income->amount_in ?? 'usd') === 'usd') {
                $income->converted_pkr = round($income->amount * $income->conversion_rate, 2);
            }

            // Sanity cap on the conversion rate, read live from Settings ->
            // Payments (not hardcoded) so it can be raised as the real USD/PKR
            // rate rises over time without a code change.
            if ($income->conversion_rate) {
                $maxRate = (float) (setting('payments.max_conversion_rate', 290) ?: 290);
                if ((float) $income->conversion_rate > $maxRate) {
                    throw ValidationException::withMessages([
                        'conversion_rate' => "Conversion rate ({$income->conversion_rate}) is higher than the maximum "
                            . "allowed ({$maxRate}). Double check the rate, or raise the maximum in Settings > Payments "
                            . 'if this is correct.',
                    ]);
                }
            }

            // Duplicate guard: one transaction id may span several orders
            // (Fiverr added hours, multiple orders inside one Upwork
            // contract), but an identical transaction id + amount + order
            // date means the same order is being entered twice.
            if ($income->transaction_id && $income->amount) {
                $duplicate = static::where('transaction_id', $income->transaction_id)
                    ->where('amount', $income->amount)
                    ->when(
                        $income->transaction_date,
                        fn($q) => $q->whereDate('transaction_date', $income->transaction_date),
                        fn($q) => $q->whereNull('transaction_date')
                    )
                    ->when($income->id, fn($q) => $q->where('id', '!=', $income->id))
                    ->first();

                if ($duplicate) {
                    throw ValidationException::withMessages([
                        'transaction_id' => "Income #{$duplicate->id} already exists with the same transaction id, "
                            . 'amount and order date. One transaction can contain multiple orders, but each order '
                            . 'must differ in amount or date - check before saving it twice.',
                    ]);
                }
            }

            // At least one attachment (invoice/screenshot) is required. Enforced
            // here rather than as a BREAD "required" validation rule, because a
            // file input is only present in the request when a NEW file is
            // chosen - a plain "required" rule would wrongly block saving an
            // edit that keeps its existing attachments untouched. Checking the
            // model's final merged attachments list (existing + newly uploaded,
            // minus anything explicitly removed) works correctly for both.
            if (empty(json_decode($income->attachments ?? '[]', true))) {
                throw ValidationException::withMessages([
                    'attachments' => 'At least one attachment (invoice or screenshot) is required for every income.',
                ]);
            }
        });
    }

    public function scopeOnlyShowToDev($query)
    {
        // Newest incomes first so dropdowns show recent orders on top.
        return $query->where('show_to_dev', 'yes')->orderByDesc('id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(UserPayment::class, 'income_id');
    }

    /**
     * Label shown in the payment-request income dropdown: id, note,
     * amount and order date so the right income is easy to spot.
     */
    public function getDropdownLabelAttribute(): string
    {
        $parts = ['#' . $this->id];
        if ($this->note) {
            $parts[] = Str::limit(trim($this->note), 45);
        }
        $parts[] = '$' . rtrim(rtrim(number_format((float) $this->amount, 2), '0'), '.');
        $date = $this->transaction_date ?: $this->created_at;
        if ($date) {
            $parts[] = \Carbon\Carbon::parse($date)->format('d M Y');
        }

        return implode(' - ', $parts);
    }

    /**
     * The income source expressed as a payment-request client_source value.
     */
    public function clientSource(): string
    {
        $source = strtolower(trim($this->source ?? ''));
        $mapped = self::CLIENT_SOURCE_MAP[$source] ?? $source;

        return in_array($mapped, UserPayment::CLIENT_SOURCES) ? $mapped : 'other';
    }
}
