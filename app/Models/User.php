<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes related to role.
     *
     * @var string
     */
    protected $DEVELOPER_ROLE_ID = 3;
    protected $CLIENT_ROLE_ID = 4;
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOnlyClient($query)
    {
        return $query->where('role_id', $this->CLIENT_ROLE_ID);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOnlyDeveloper($query)
    {
        return $query->where('role_id', $this->DEVELOPER_ROLE_ID);
    }
}
