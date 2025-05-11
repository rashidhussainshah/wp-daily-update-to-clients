<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DeveloperCardCategory extends Pivot
{
    protected $table = 'developer_card_categories';
    
    protected $fillable = [
        'developer_card_id',
        'developer_category_id'
    ];
} 