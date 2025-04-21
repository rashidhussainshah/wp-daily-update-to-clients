<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPortfolio extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'title',
        'description', // Add this since you have this in your migration
        'image',
        'experience',
        'expertise',
        'knowledge',
        'client_name', // Add client_name
        'client_review', // Add client_review
        'whatsapp',
        'linkedin',
        'facebook',
        'instagram',
        'user_id' // Don't forget to add the user_id field for mass assignment
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cast the following attributes to arrays
    protected $casts = [
        'experience' => 'array',
        'expertise' => 'array',
        'knowledge' => 'array',
        'client_name' => 'array', // Ensure it's cast to an array
        'client_review' => 'array', // Ensure it's cast to an array
    ];

    // Define relationship with Expertise model (many-to-many)
    public function expertises()
    {
        return $this->belongsToMany(Expertise::class);
    }

    // Define relationship with DeveloperCategory model (many-to-many)
    public function developerCategories()
    {
        return $this->belongsToMany(DeveloperCategory::class);
    }

    // Define relationship with DeveloperCard model (many-to-many)
    public function developerCards()
    {
        return $this->belongsToMany(DeveloperCard::class);
    }

    // Define relationship with DeveloperCardExpertise model (many-to-many)
    public function developerCardExpertises()
    {
        return $this->belongsToMany(DeveloperCardExpertise::class);
    }
}
