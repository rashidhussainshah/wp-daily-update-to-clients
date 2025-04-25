<?php

namespace App\utils\traits;

use Illuminate\Support\Facades\Auth;

trait CommonRelationship
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeCurrentDeveloper($query)
    {
        return $query->where('developer_id', Auth::user()->id);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCurrentUserByUserId($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }
}
