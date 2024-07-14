<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;
    protected $fillable = ['developer_id', 'checkin_at', 'checkout_at', 'today_work_plan', 'end_of_day_report'];

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
