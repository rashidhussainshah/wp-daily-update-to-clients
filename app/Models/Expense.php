<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, CommonRelationship, SoftDeletes;

    const CREDIT_TO_DEV_STATUS = 'credit_to_dev';

    protected $dates = ['deleted_at'];
    public $allow_export_all = true;

}
