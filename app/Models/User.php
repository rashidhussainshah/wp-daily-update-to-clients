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
        // Disabled: Clockify free plan rejects adding users via API (400 "Upgrade to paid plan").
        // Re-enable if the workspace is upgraded, or sync later via clockify:sync-users.
        // static::created(function ($user) {
        //     $clockifyService = app(ClockifyService::class);
        //     $clockifyUser = $clockifyService->createUser($user->email, $user->name);
        //
        //     // Save the Clockify user ID
        //     $user->clockify_user_id = $clockifyUser['id'];
        //     $user->save();
        // });

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
    /** Only user allowed to mark payment requests as paid. */
    const RASHID_USER_ID = 1;
    public $disable_export = true;
    protected $dates = ['deleted_at'];

    const ADMINISTRATOR_ROLE_NAME = 'Administrator';
    const ACCOUNTANT_ROLE_NAME = 'Accountant';
    const DEVELOPER_ROLE_NAME = 'Developer';
    /** Actual role name in the roles table (typo included). */
    const BUSINESS_DEVELOPER_ROLE_NAME = 'Bussiness Developer';

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
    private $HOMEY_CLIENT_ROLE_ID = 61;
    // WebPenter IT Academy student - deliberately a DISTINCT role from
    // STUDENT_ROLE_ID/RYK_STUDENT_ROLE_ID above (both 12, an unrelated legacy
    // student-fee system) so Academy students are never ambiguously picked up
    // by that old system's queries. Fallback only - create the real "IT
    // Academy Student" role in Voyager admin and set
    // `academy.it_academy_student_role_id` to its actual id.
    private $IT_ACADEMY_STUDENT_ROLE_ID = 70;

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
    public function scopeOnlyItAcademyStudent($query)
    {
        return $query->where('role_id', setting('academy.it_academy_student_role_id') ?? $this->IT_ACADEMY_STUDENT_ROLE_ID);
    }
    public function isItAcademyStudent(): bool
    {
        return (int) $this->role_id === (int) (setting('academy.it_academy_student_role_id') ?? $this->IT_ACADEMY_STUDENT_ROLE_ID);
    }

    /**
     * Academy instructor/reviewer capabilities are ADDITIVE (academy_staff_roles),
     * separate from this user's one primary role_id - see that table's migration
     * for why (a Developer-role user can also be an Academy Reviewer).
     */
    public function academyStaffRoles()
    {
        return $this->hasMany(\App\Models\AcademyStaffRole::class);
    }
    public function academyEnrollments()
    {
        return $this->hasMany(\App\Models\DeveloperAcademyEnrollment::class);
    }
    public function instructedAcademyEnrollments()
    {
        return $this->hasMany(\App\Models\DeveloperAcademyEnrollment::class, 'instructor_id');
    }
    public function academyCertificates()
    {
        return $this->hasMany(\App\Models\AcademyCertificate::class);
    }
    public function isAcademyInstructor(): bool
    {
        return $this->isItAcademyStudent() ? false : $this->academyStaffRoles()
            ->where('capability', \App\Models\AcademyStaffRole::CAPABILITY_INSTRUCTOR)->exists();
    }
    public function isAcademyReviewer(): bool
    {
        return $this->isItAcademyStudent() ? false : $this->academyStaffRoles()
            ->where('capability', \App\Models\AcademyStaffRole::CAPABILITY_REVIEWER)->exists();
    }
    public function scopeAcademyInstructors($query)
    {
        return $query->whereHas('academyStaffRoles', fn ($q) => $q->where('capability', \App\Models\AcademyStaffRole::CAPABILITY_INSTRUCTOR));
    }
    public function scopeAcademyReviewers($query)
    {
        return $query->whereHas('academyStaffRoles', fn ($q) => $q->where('capability', \App\Models\AcademyStaffRole::CAPABILITY_REVIEWER));
    }

    /**
     * Excludes Homey Client role users (marketing-campaign leads imported
     * via ImportHomeyClients, not real portal staff) - used to scope the
     * admin Users listing so it shows everyone else instead.
     */
    public function scopeExcludeHomeyClient($query)
    {
        return $query->where('role_id', '!=', setting('admin.homey_client_role_id') ?? $this->HOMEY_CLIENT_ROLE_ID);
    }

    /**
     * Per-instance check mirroring scopeBusinessDeveloper() - used wherever a
     * single user (not a query) needs to be classified, e.g. deciding payment
     * request flow. Role-based so any current/future business developer is
     * recognized, not just a hardcoded list of user ids.
     */
    public function isBusinessDeveloper(): bool
    {
        return (int) $this->role_id === (int) (setting('academy.business_developer_role_id') ?? $this->BUSINESS_DEVELOPER_ROLE_ID);
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
