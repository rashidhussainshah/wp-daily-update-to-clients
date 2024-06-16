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
            return $query;
        } else {
            return $query->where('developer_id', Auth::user()->id);
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
}
