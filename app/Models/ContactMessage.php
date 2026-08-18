<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'subject',
        'phone',
        'message',
        'status',
        'source_domain',
        'referer_url',
        'ip_address',
        'user_agent',
        'country',
        'region',
        'city',
        'extra_fields',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'extra_fields' => 'array',
    ];
}
