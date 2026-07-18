<?php

namespace App\Services;

use App\Models\Income;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Validation\ValidationException;

class PaymentCalculationService
{
    // Note: total_earning (and every calculation below) is always treated as
    // USD. Income::amount_in (USD/PKR) is not read anywhere in this service
    // or in DeveloperPaymentController::store() - it only controls Income's
    // own converted_pkr tracking figure, not share/payable calculation.

    /**
     * Platform fees deducted before any share is calculated.
     * fiverr 20%, upwork 10%, payoneer/other no fee.
     */
    const FEE_RATES = [
        'fiverr' => 0.20,
        'upwork' => 0.10,
    ];

    /** Total share of net earning reserved for development partners. */
    const DEV_PARTNER_CAP = 0.375;

    /** Fallback commission rates when users.percentage is not set. */
    const AYUB_DEFAULT_RATE = 0.05;
    const ALI_HASAN_DEFAULT_RATE = 0.04;

    /** Allowed rounding drift when comparing allocations against caps. */
    const EPSILON = 0.01;

    public function feeRate(?string $clientSource): float
    {
        return self::FEE_RATES[$clientSource] ?? 0.0;
    }

    /**
     * Earning left after the platform (fiverr/upwork) takes its fee.
     */
    public function netAfterFee(float $totalEarning, ?string $clientSource): float
    {
        return $totalEarning * (1 - $this->feeRate($clientSource));
    }

    /**
     * Share rate of a development partner (37.5% unless overridden per user).
     */
    public function developmentPartnerRate(User $user): float
    {
        return (float) ($user->percentage ?? self::DEV_PARTNER_CAP);
    }

    /**
     * Commission rate of a business developer (Ayub 6%, Ali Hasan 5% unless overridden per user).
     */
    public function businessDeveloperRate(User $user): float
    {
        if ($user->percentage) {
            return (float) $user->percentage;
        }

        return $user->id == User::AYUB_USER_ID
            ? self::AYUB_DEFAULT_RATE
            : self::ALI_HASAN_DEFAULT_RATE;
    }

    /**
     * Dollar share for a development partner request.
     */
    public function developmentPartnerEarning(float $totalEarning, ?string $clientSource, User $partner): float
    {
        return round($this->netAfterFee($totalEarning, $clientSource) * $this->developmentPartnerRate($partner), 2);
    }

    /**
     * Dollar share for a business developer commission.
     */
    public function businessDeveloperEarning(float $totalEarning, ?string $clientSource, User $businessDeveloper): float
    {
        return round($this->netAfterFee($totalEarning, $clientSource) * $this->businessDeveloperRate($businessDeveloper), 2);
    }

    public function payable(float $devEarning, ?float $currencyRate): ?float
    {
        return $currencyRate ? round($devEarning * $currencyRate, 2) : null;
    }

    /**
     * Existing (not deleted) payment request of a user against an income, if any.
     */
    public function existingRequestForIncome(int $incomeId, int $developerId): ?UserPayment
    {
        return UserPayment::where('income_id', $incomeId)
            ->where('developer_id', $developerId)
            ->first();
    }

    /**
     * Hard block: a user may only have one payment request per income.
     */
    public function assertNoDuplicate(int $incomeId, int $developerId, string $who = 'You'): void
    {
        $existing = $this->existingRequestForIncome($incomeId, $developerId);
        if ($existing) {
            throw ValidationException::withMessages([
                'income_id' => "{$who} already have a payment request (#{$existing->id}, status: {$existing->status}) against this income. Duplicate requests are not allowed - edit or delete the existing one first.",
            ]);
        }
    }

    /**
     * Hard block: the sum of shares of one type against an income can never
     * exceed its cap (37.5% of net for development partners, the business
     * developer's own rate for commissions).
     *
     * @param float $newShare      the dev_earning being added
     * @param float $capRate       0.375 for dev partners, the BD rate for commissions
     */
    public function assertIncomeCapNotExceeded(Income $income, string $shareType, ?string $clientSource, float $newShare, float $capRate, ?int $excludePaymentId = null): void
    {
        $net = $this->netAfterFee((float) $income->amount, $clientSource);
        $cap = round($net * $capRate, 2);

        $query = UserPayment::where('income_id', $income->id)
            ->where('share_type', $shareType);
        if ($excludePaymentId) {
            $query->where('id', '!=', $excludePaymentId);
        }
        $allocated = (float) $query->sum('dev_earning');

        if (($allocated + $newShare) > ($cap + self::EPSILON)) {
            $remaining = max(round($cap - $allocated, 2), 0);
            $label = $shareType === UserPayment::SHARE_TYPE_DEVELOPMENT_PARTNER
                ? 'development partners (37.5% of net)'
                : 'this business developer';
            throw ValidationException::withMessages([
                'total_earning' => "This request exceeds the allowed share for {$label} on income #{$income->id}. "
                    . "Income: \${$income->amount}, net after fee: \$" . round($net, 2)
                    . ", cap: \${$cap}, already allocated: \$" . round($allocated, 2)
                    . ", remaining: \${$remaining}, requested: \${$newShare}.",
            ]);
        }
    }

    /**
     * Hard block: a request's total earning cannot exceed the linked income amount.
     */
    public function assertEarningWithinIncome(Income $income, float $totalEarning): void
    {
        if ($totalEarning > ((float) $income->amount + self::EPSILON)) {
            throw ValidationException::withMessages([
                'total_earning' => "Total earning (\${$totalEarning}) cannot be more than the linked income #{$income->id} amount (\${$income->amount}).",
            ]);
        }
    }
}
