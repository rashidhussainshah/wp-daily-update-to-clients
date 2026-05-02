<?php

namespace App\Models;

use App\Services\ClockifyService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // Named scope key — use withoutGlobalScope(self::SCOPE_EXCLUDE_HOMEY) to opt out
    const SCOPE_EXCLUDE_HOMEY = 'exclude_homey_clients';

    protected static function booted()
    {
        static::created(function ($user) {
            $clockifyService = app(ClockifyService::class);
            $clockifyUser = $clockifyService->createUser($user->email, $user->name);

            // Save the Clockify user ID
            $user->clockify_user_id = $clockifyUser['id'];
            $user->save();
        });

        // Keep homey_client (marketing imports) out of all normal User queries.
        // Console commands and campaign dispatch use DB::table() or withoutGlobalScope().
        static::addGlobalScope(self::SCOPE_EXCLUDE_HOMEY, function (Builder $builder) {
            $roleId = \Illuminate\Support\Facades\Cache::remember('role_id_homey_client', 3600, function () {
                return \TCG\Voyager\Models\Role::where('name', 'homey_client')->value('id');
            });
            if ($roleId) {
                $builder->where('role_id', '!=', $roleId);
            }
        });
    }
    const AYUB_USER_ID = 3;
    const ALI_HASAN_USER_ID = 147;
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
    private $RYK_STUDENT_ROLE_ID = 12;
    private $ONLINE_STUDENT_ROLE_ID = 41;
    private $BUSINESS_DEVELOPER_ROLE_ID = 33;

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
    public function scopeRykOnlyStudent($query)
    {
        return $query->where('role_id', setting('academy.ryk_student_role_id') ?? $this->RYK_STUDENT_ROLE_ID);
    }
    public function scopeOnlineOnlyStudent($query)
    {
        return $query->where('role_id', setting('academy.online_student_role_id') ?? $this->ONLINE_STUDENT_ROLE_ID);
    }
    public function scopeBusinessDeveloper($query)
    {
        return $query->where('role_id', setting('academy.business_developer_role_id') ?? $this->BUSINESS_DEVELOPER_ROLE_ID);
    }

    public function clientPortfolios()
    {
        return $this->hasMany(ClientPortfolio::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
