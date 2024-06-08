<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{
    use HasFactory, CommonRelationship;
}
