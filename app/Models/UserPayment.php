<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserPayment extends Model
{
    use HasFactory, CommonRelationship, SoftDeletes;

    public $allow_export_all = true;

    const APPROVED_STATUS = 'Approved';
    const REQUESTED_STATUS = 'Requested';

    const SHARE_TYPE_DEVELOPMENT_PARTNER = 'development_partner';
    const SHARE_TYPE_BUSINESS_DEVELOPER = 'business_developer';

    const EARNING_TYPE_PROJECT = 'project';
    const EARNING_TYPE_SALARY_EMPLOYEE = 'salary_employee';

    const CLIENT_SOURCES = ['fiverr', 'upwork', 'payonner', 'other'];

//    public function setDeveloperIdAttribute()
//    {
//        if ($this->isDeveloper() && auth()->user()->email != 'ayubkhokar786@gmail.com') {
//            $this->attributes['developer_id'] = Auth::user()->id;
//        } else {
//            $ayubUser = User::where('email', 'ayubkhokhar786@gmail.com')->first();
//            $this->attributes['developer_id'] = $ayubUser->id;
//
//        }
//    }
    public static function getPayable($selectedUserId)
    {
        return UserPayment::where('developer_id', $selectedUserId)->approved()/*->notPaid()*/->sum('payable');
    }
    public static function getPaid($selectedUserId)
    {
        return UserPayment::where('developer_id', $selectedUserId)->approved()/*->paid()*/->sum('paid');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', UserPayment::APPROVED_STATUS);
    }
    public function scopePaid($query)
    {
        return $query->whereNotNull('paid');
    }
    public function scopeNotPaid($query)
    {
        return $query->whereNull('paid');
    }

    public function scopeRequested($query)
    {
        return $query->where('status', UserPayment::REQUESTED_STATUS);
    }

    public function scopeCurrentUserAndManagement($query)
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::ADMINISTRATOR_ROLE_NAME || Auth::user()->role->name == USER::ACCOUNTANT_ROLE_NAME)) {
            return $query->orderBy('created_at', 'desc');
        } else {
            return $query->where('developer_id', Auth::user()->id)->orderBy('created_at', 'desc');
        }
    }

    private function isDeveloper()
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::DEVELOPER_ROLE_NAME)) {
            return true;
        }
        return false;
    }

    /**
     * @return BelongsTo
     */
    public function project(): belongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function projectTarget(): belongsTo
    {
        return $this->belongsTo(ProjectTarget::class);
    }

    public function developer(): belongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function income(): belongsTo
    {
        return $this->belongsTo(Income::class);
    }

    public function businessDeveloper(): belongsTo
    {
        return $this->belongsTo(User::class, 'select_business_developer_id');
    }

    /**
     * The auto-generated business developer commission linked to this
     * development partner request.
     */
    public function linkedCommission()
    {
        return $this->hasOne(UserPayment::class, 'second_entry_id');
    }

    /**
     * The development partner request this commission was generated from.
     */
    public function sourceRequest(): belongsTo
    {
        return $this->belongsTo(UserPayment::class, 'second_entry_id');
    }

    /**
     * Who changed what on this request, newest first (see UserPaymentObserver).
     */
    public function logs(): HasMany
    {
        return $this->hasMany(UserPaymentLog::class)->latest();
    }

    /**
     * SQL fragment (with bindings) for the absolute PKR difference between
     * the stored payable and share x currency rate.
     */
    public static function payableMismatchExpression(): array
    {
        return [
            'sql' => 'ABS(payable - (dev_earning * currency_current_rate))',
            'bindings' => [],
        ];
    }

    /**
     * SQL fragment (with bindings) for the absolute USD difference between
     * the stored share (dev_earning) and what it should be: the income's
     * net-after-fee amount times a flat development partner rate you pick
     * explicitly (35% or 37.5% - the two rates that have applied over
     * time). Explicit rather than date-based so it also works on rows with
     * a bad/missing created_at (the admin picks which rate to check). A
     * users.percentage override still wins when one is set. Development
     * partner rows only - see scopeFilter()'s use of this expression.
     */
    public static function shareMismatchExpression(float $rate): array
    {
        $sql = "ABS(dev_earning - ROUND(
                (total_earning * (1 - CASE client_source
                    WHEN 'fiverr' THEN 0.20
                    WHEN 'upwork' THEN 0.10
                    ELSE 0 END))
                * COALESCE(
                    (SELECT percentage FROM users WHERE users.id = user_payments.developer_id),
                    ?
                ), 2))";

        return [
            'sql' => $sql,
            'bindings' => [$rate],
        ];
    }

    /**
     * Listing filters used by the user-payments browse page.
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['ids'] ?? null, function ($q, $v) {
                // accepts "341", "341,350" or "341 350 402"
                $ids = array_filter(array_map('intval', preg_split('/[\s,]+/', $v, -1, PREG_SPLIT_NO_EMPTY)));
                return $ids ? $q->whereIn('id', $ids) : $q;
            })
            ->when($filters['developer_id'] ?? null, fn($q, $v) => $q->where('developer_id', $v))
            ->when($filters['business_developer_id'] ?? null, fn($q, $v) => $q->where('select_business_developer_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['share_type'] ?? null, fn($q, $v) => $q->where('share_type', $v))
            ->when($filters['earning_type'] ?? null, fn($q, $v) => $q->where('earning_type', $v))
            ->when($filters['client_source'] ?? null, fn($q, $v) => $q->where('client_source', $v))
            ->when($filters['project_id'] ?? null, fn($q, $v) => $q->where('project_id', $v))
            ->when($filters['income_id'] ?? null, fn($q, $v) => $q->where('income_id', $v))
            ->when(isset($filters['paid_state']) && $filters['paid_state'] === 'paid', fn($q) => $q->paid())
            ->when(isset($filters['paid_state']) && $filters['paid_state'] === 'unpaid', fn($q) => $q->notPaid())
            ->when(isset($filters['generated']) && $filters['generated'] === 'system', fn($q) => $q->where('generated_by_system', true))
            ->when(isset($filters['generated']) && $filters['generated'] === 'manual', fn($q) => $q->where('generated_by_system', false))
            // Payable sanity filter: rows where the stored payable does not
            // match share x rate (differences up to 20 PKR are ignored as
            // rounding).
            ->when(isset($filters['mismatch']) && $filters['mismatch'] === '1', function ($q) {
                ['sql' => $sql, 'bindings' => $bindings] = self::payableMismatchExpression();
                $q->whereNotNull('currency_current_rate')
                    ->whereNotNull('payable')
                    ->whereRaw("{$sql} > 20", $bindings);
            })
            // Share sanity filter (development partners only): rows where
            // the stored share doesn't match a flat 35% or 37.5% of the net
            // earning (differences up to $0.05 are ignored as rounding).
            ->when(isset($filters['mismatch']) && in_array($filters['mismatch'], ['35', '37.5'], true), function ($q) use ($filters) {
                $rate = (float) $filters['mismatch'] / 100;
                ['sql' => $sql, 'bindings' => $bindings] = self::shareMismatchExpression($rate);
                $q->where('share_type', self::SHARE_TYPE_DEVELOPMENT_PARTNER)
                    ->whereNotNull('total_earning')
                    ->whereNotNull('dev_earning')
                    ->whereRaw("{$sql} > 0.05", $bindings);
            })
            ->when($filters['date_from'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v));
    }
}
