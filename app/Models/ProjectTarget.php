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

    /** Exposes dropdown_label as a relationship label option in Voyager BREAD. */
    public $additional_attributes = ['dropdown_label'];

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

    /**
     * Newest targets first - used by the payment-request dropdown (BREAD
     * relationship scope).
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('id');
    }

    /**
     * Label shown in the payment-request Project Target dropdown: id + title,
     * so it's easy to tell targets with the same/similar title apart.
     */
    public function getDropdownLabelAttribute(): string
    {
        return '#' . $this->id . ' - ' . $this->title;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTargetTask::class)->whereDate('created_at', today());
    }


}
