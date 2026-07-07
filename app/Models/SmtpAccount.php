<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpAccount extends Model
{
    protected $fillable = [
        'name', 'host', 'port', 'encryption',
        'username', 'password', 'from_address', 'from_name', 'is_active',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'port'      => 'integer',
    ];

    public function getDecryptedPasswordAttribute(): string
    {
        try {
            return decrypt($this->password);
        } catch (\Throwable) {
            return '';
        }
    }
}
