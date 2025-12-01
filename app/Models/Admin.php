<?php

namespace App\Models;

use App\Traits\StatusColumn;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, StatusColumn;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'profile_image',
        'username',
        'email',
        'password',
        'role',
        'status',
        'last_login',
        'theme'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
    ];

    protected $attributes = [
        'role' => self::ROLE_TYPE_ADMIN,
        'status' => self::STATUS_IN_ACTIVE,
    ];

    public const PASSWORD_REGEX = "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$";
    public const PASSWORD_HINT_MESSAGE = "Minimum eight characters, at least one letter, one number and one special character.";
    public const ROLE_TYPE_SUPER_ADMIN = 1;
    public const ROLE_TYPE_ADMIN = 2;
 
    protected function role(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  self::getRoleListOptions()[$value],
        );
    }

    public static function getRoleList(): array
    {
        return [
            self::ROLE_TYPE_SUPER_ADMIN,
            self::ROLE_TYPE_ADMIN,
        ];
    }

    public static function getRoleListOptions(): array
    {
        return [
            self::ROLE_TYPE_SUPER_ADMIN => __('Super Admin'),
            self::ROLE_TYPE_ADMIN => __('Admin'),
        ];
    }

    public static function getRole(int $roleType): ?string
    {
        return self::getRoleListOptions()[$roleType] ?? null;
    }

    public function getResetPasswordLink(): string
    {
        return URL::temporarySignedRoute(
            'admin.reset.password',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->id,
                'hash' => sha1($this->email),
            ]
        );
    }
}
