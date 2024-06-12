<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Eod extends Model
{
    use HasFactory, CommonRelationship;
    public $disable_export = true;

    /**
     * Set the developer_id.
     *
     * @return void
     */
    public function setDeveloperIdAttribute()
    {
        $this->attributes['developer_id'] = Auth::user()->id;
    }
    public function scopeCurrentDeveloperORClient($query)
    {
        if (Auth::user() && Auth::user()->role_id == User::CLIENT_ID) {
            return $query->where('client_id', Auth::user()->id);
        } else {
            return $query->where('developer_id', Auth::user()->id);
        }
    }
}
