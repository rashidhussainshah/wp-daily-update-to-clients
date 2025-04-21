<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    use HasFactory;
//    protected $table = 'expertise';
    // Define inverse relationship with ClientPortfolio model
    public function clientPortfolios()
    {
        return $this->belongsToMany(ClientPortfolio::class);
    }
}
