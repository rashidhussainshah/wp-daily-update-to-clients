<?php

namespace App\Models;

use App\utils\traits\CommonRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EodConfiguration extends Model
{
    use HasFactory, CommonRelationship;
    public $disable_export = true;

    /*
     *  Accessors & Mutators
     *------------------------
    */

    /**
     * @return BelongsTo
     */
    public function client(): belongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * @return BelongsTo
     */
    public function developer(): belongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
    public function scopeDefaultSettingForEod($query, $val = true)
    {
        return $query->where('is_default_setting_for_eod', $val);
    }
}
