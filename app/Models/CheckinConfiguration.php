<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinConfiguration extends Model
{
    use HasFactory, CommonRelationship;
    protected $fillable = [
        'developer_id',
        'slack_webhook_url',
        'designation',
    ];

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');

    }

}
