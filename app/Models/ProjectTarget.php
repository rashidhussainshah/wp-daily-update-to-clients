<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ProjectTarget extends Model
{
    use HasFactory, CommonRelationship;

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be appended in models.
     *
     * @var array
     */
//    protected $appends = [
//        'task_hours',
//        'task_minutes',
//    ];


    /**
     * Set the developer_id.
     *
     * @return void
     */
    public function setDeveloperIdAttribute()
    {
        $this->attributes['developer_id'] = Auth::user()->id;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTargetTask::class)->whereDate('created_at', today());
    }


}
