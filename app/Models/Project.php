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

    /** Exposes dropdown_label as a relationship label option in Voyager BREAD. */
    public $additional_attributes = ['dropdown_label'];

    public function targets(): HasMany
    {
        return $this->hasMany(ProjectTarget::class);
    }

    /**
     * Newest projects first - used by the payment-request dropdown (BREAD
     * relationship scope).
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('id');
    }

    /**
     * Label shown in the payment-request Project dropdown: id + name, so
     * it's easy to tell projects with the same/similar name apart.
     */
    public function getDropdownLabelAttribute(): string
    {
        return '#' . $this->id . ' - ' . $this->name;
    }

    public function eodConfiguration(): HasOne
    {
        return $this->hasOne(EodConfiguration::class)->whereDeveloperId(Auth::user()->id);
    }

}
