<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DeveloperClient extends Model
{
    
    public $table = 'developer_clients';

    public function developerCard()
    {
        return $this->belongsTo(DeveloperCard::class, 'developer_id');
    }
}
