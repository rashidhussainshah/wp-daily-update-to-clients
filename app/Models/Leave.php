<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    use HasFactory;
    public function setUsersIdAttribute()
    {
        $this->attributes['user_id'] = Auth::user()->id;
    }
}
