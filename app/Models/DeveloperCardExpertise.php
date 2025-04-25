<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperCardExpertise extends Model
{
    use HasFactory;

    // Define inverse relationship with ClientPortfolio model
    public function clientPortfolios()
    {
        return $this->belongsToMany(ClientPortfolio::class);
    }
}
