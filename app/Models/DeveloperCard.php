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
    // Automatically append this attribute to the model's JSON form
    protected $appends = ['image_url'];

    /**
     * Accessor for the complete image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->profile) {
            return url('storage/' . $this->profile);
        }

        return null; // or a default image path
    }

    public function developerCategory()
    {
        return $this->belongsTo(DeveloperCategory::class, 'developer_category_id');
    }

    public function expertises()
    {
        return $this->belongsToMany(Expertise::class, 'developer_card_expertises');
    }

    public function categories()
    {
        return $this->belongsToMany(DeveloperCategory::class, 'developer_card_categories')
            ->using(DeveloperCardCategory::class);
    }

    // Define inverse relationship with ClientPortfolio model
    public function clientPortfolios()
    {
        return $this->belongsToMany(ClientPortfolio::class);
    }

    public function developerClients()
    {
        return $this->belongsToMany(Client::class, 'developer_clients');
    }

    public function developerInformation()
    {
        return $this->hasOne(DeveloperInformation::class, 'id');
    }

    public function knowledge()
    {
        return $this->hasMany(Knowledge::class, 'user_id', 'id');
    }
}
