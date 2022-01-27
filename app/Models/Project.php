<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    public function scopeCurrentUser($query)
    {
        return $query->where('client_id', Auth::user()->id);
    }
}
