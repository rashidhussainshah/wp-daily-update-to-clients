<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const CLIENT_ID = 4;
    public $disable_export = true;
    protected $dates = ['deleted_at'];

    const ADMINISTRATOR_ROLE_NAME = 'Administrator';
    const ACCOUNTANT_ROLE_NAME = 'Accountant';
    const DEVELOPER_ROLE_NAME = 'Developer';

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
     * @var mixed
     */
    private $ADMINISTRATOR_ROLE_ID = 1;
    private $STUDENT_ROLE_ID = 12;
    private $ONLINE_STUDENT_ROLE_ID = 41;
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
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOnlyDeveloper($query)
    {
        return $query->where('role_id', setting('admin.developer_role_id') ?? $this->DEVELOPER_ROLE_ID);
    }
    public function scopeOnlyAdministrator($query)
    {
        return $query->where('role_id', setting('admin.administrator_role_id') ?? $this->ADMINISTRATOR_ROLE_ID);
    }
    public function scopeOnlyStudent($query)
    {
        return $query->where('role_id', setting('academy.student_role_id') ?? $this->STUDENT_ROLE_ID);
    }
    public function scopeOnlineOnlyStudent($query)
    {
        return $query->where('role_id', setting('academy.online_student_role_id') ?? $this->ONLINE_STUDENT_ROLE_ID);
    }
}
