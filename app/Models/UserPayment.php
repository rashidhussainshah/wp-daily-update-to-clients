<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Listing filters used by the user-payments browse page.
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
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
            ->when($filters['date_from'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v));
    }
}
