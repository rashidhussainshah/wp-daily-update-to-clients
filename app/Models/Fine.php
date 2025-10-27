<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Fine extends Model
{
    use HasFactory, CommonRelationship;
    protected $fillable = ['amount', 'user_id', 'reason', 'date', 'note', 'paid'];
    public function scopeCurrentUserAndManagement($query)
    {
        if (Auth::user()->role && (Auth::user()->role->name == USER::ADMINISTRATOR_ROLE_NAME || Auth::user()->role->name == USER::ACCOUNTANT_ROLE_NAME)) {
            return $query->orderBy('created_at', 'desc');
        } else {
            return $query->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        }
    }
}
