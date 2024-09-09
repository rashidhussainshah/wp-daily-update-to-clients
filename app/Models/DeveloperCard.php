<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'developer_category_id',
        'name',
        'designation',
        'expertises',
    ];

    public function developerCategory()
    {
        return $this->belongsTo(DeveloperCategory::class, 'developer_category_id');
    }

    public function expertises()
    {
        return $this->belongsToMany(Expertise::class, 'developer_card_expertises');
    }
}
