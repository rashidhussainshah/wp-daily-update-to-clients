<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserPayment extends Model
{
    use HasFactory, CommonRelationship, SoftDeletes;

    const APPROVED_STATUS = 'Approved';

    public function setDeveloperIdAttribute()
    {
        if ($this->isDeveloper()) {
            $this->attributes['developer_id'] = Auth::user()->id;
        }
    }

    public function scopeApproved($query)
    {
        return $query->where('status', UserPayment::APPROVED_STATUS);
    }

    public function scopeCurrentUserAndManagement($query)
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::ADMINISTRATOR_ROLE_NAME || Auth::user()->role->name == USER::ACCOUNTANT_ROLE_NAME )) {
            return $query;
        } else {
            return $query->where('developer_id', Auth::user()->id);
        }
    }

    private function isDeveloper()
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::DEVELOPER_ROLE_NAME )) {
            return true;
        }
        return false;
    }

}
