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
    const IN_PKR = 'pkr';
    const IN_USD = 'usd';

    protected $dates = ['deleted_at'];
    public $allow_export_all = true;

    public static function getAdvance($selectedUserId, string $usdOrPkr)
    {
        return Expense::where('developer_id', $selectedUserId)->where('purpose', Expense::CREDIT_TO_DEV_STATUS)->where('amount_in', $usdOrPkr)->sum('amount'); // payment that given advance in pkr
    }

}
