<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function scopeCurrentUser($query)
    {
        return $query->where('client_id', Auth::user()->id);
    }
}
