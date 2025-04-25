<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory, CommonRelationship;
    protected $fillable = ['amount', 'user_id', 'reason', 'date', 'note'];
}
