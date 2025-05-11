<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperCategory extends Model
{
    use HasFactory;
    // Define inverse relationship with ClientPortfolio model
    public function clientPortfolios()
    {
        return $this->belongsToMany(ClientPortfolio::class);
    }

    public function developerCards()
    {
        return $this->belongsToMany(DeveloperCard::class, 'developer_card_categories')
            ->using(DeveloperCardCategory::class);
    }
}
