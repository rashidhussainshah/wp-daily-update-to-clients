<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectTargetTask extends Model
{
    use HasFactory, CommonRelationship;
    protected $dates = ['deleted_at'];
    /**
     * Set the developer_id.
     *
     * @return void
     */
    public function setDeveloperIdAttribute()
    {
        $this->attributes['developer_id'] = Auth::user()->id;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeToday($query)
    {
                return $query->whereDate('date', '=', today()->toDateString());

    }

}
