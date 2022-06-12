<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function targets(): HasMany
    {
        return $this->hasMany(ProjectTarget::class);
    }

    public function eodConfiguration(): HasOne
    {
        return $this->hasOne(EodConfiguration::class)->whereDeveloperId(Auth::user()->id);
    }

}
