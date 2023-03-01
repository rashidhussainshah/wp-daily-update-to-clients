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
    public function setDeveloperIdAttribute()
    {
        $this->attributes['developer_id'] = Auth::user()->id;
    }
}
