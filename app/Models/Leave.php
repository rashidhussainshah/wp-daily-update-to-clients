<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    use HasFactory;
    public function setUserIdAttribute()
    {
        $this->attributes['user_id'] = Auth::user()->id;
    }
    public function scopeCurrentUser($query)
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::ADMINISTRATOR_ROLE_NAME )) {
            return $query;
        } else {
            return $query->where('user_id', Auth::user()->id);
        }
    }
}
