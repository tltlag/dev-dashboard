<?php

namespace App\Models;

use App\Traits\StatusColumn;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use StatusColumn;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'username',
        'profile_image',
        'dob',
        'sex',
        'role',
        'status',
        'last_login',
        'approved',
        'wildixin_id',
        'wildixin_response',
        'extension_number',
        'last_call_history_page_number',
        'clockodo_emp_id',
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
        'role' => self::ROLE_TYPE_EMPLOYEE,
        'status' => self::STATUS_IN_ACTIVE,
        'approved' => self::APPROVED_STATUS_PENDING,
    ];

    public const USERNAME_REGEX = '^[A-Za-z][A-Za-z0-9_]{7,29}$';
    public const PASSWORD_REGEX = "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$";
    public const PHONE_REGEX = "^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$";
    public const USERNAME_HINT_MESSAGE = "Usernames should start with an alphabet letter, have a minimum of 8 characters, a maximum of 29 characters, and can only include underscores (_).";
    public const PASSWORD_HINT_MESSAGE = "Minimum eight characters, at least one letter, one number and one special character.";

    public const SEX_TYPE_MALE = 'M';
    public const SEX_TYPE_FEMALE = 'F';

    public const ROLE_TYPE_EMPLOYEE = 1;

    public const APPROVED_STATUS_PENDING = 0;
    public const APPROVED_STATUS_APPROVED = 10;
    public const APPROVED_STATUS_REJECTED = 20;

    public ?BexioEmployee $bexioEmployee = null;

    public static function getSexList(): array
    {
        return [
            self::SEX_TYPE_MALE,
            self::SEX_TYPE_FEMALE,
        ];
    }

    public static function getSexListOptions(): array
    {
        return [
            self::SEX_TYPE_MALE => __('Male'),
            self::SEX_TYPE_FEMALE => __('Female'),
        ];
    }

    public static function getSex(string $sex): ?string
    {
        return self::getSexListOptions()[$sex] ?? null;
    }

    public static function getRoleList(): array
    {
        return [
            self::ROLE_TYPE_EMPLOYEE,
        ];
    }

    public static function getRoleListOptions(): array
    {
        return [
            self::ROLE_TYPE_EMPLOYEE => __('Employee'),
        ];
    }

    public static function getRole(int $roleType): ?string
    {
        return self::getRoleListOptions()[$roleType] ?? null;
    }

    public static function getApprovalStatusList(): array
    {
        return [
            self::APPROVED_STATUS_PENDING,
            self::APPROVED_STATUS_APPROVED,
            self::APPROVED_STATUS_REJECTED,
        ];
    }

    public static function getApprovalStatusListOptions(): array
    {
        return [
            self::APPROVED_STATUS_PENDING => __('Pending'),
            self::APPROVED_STATUS_APPROVED => __('Approved'),
            self::APPROVED_STATUS_REJECTED => __('Rejected'),
        ];
    }

    public static function getApprovalStatus(int $status): ?string
    {
        return self::getApprovalStatusListOptions()[$status] ?? null;
    }

    public function getEmailVeifyLink(): string
    {
        return URL::temporarySignedRoute(
            'email.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->id,
                'hash' => sha1($this->email),
            ]
        );
    }

    public function getResendEmailVeifyLink(): string
    {
        return URL::temporarySignedRoute(
            'resend.email.verify.link',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->id,
                'hash' => sha1($this->email),
            ]
        );
    }

    public function getResetPasswordLink(): string
    {
        $route = 'employee';

        return URL::temporarySignedRoute(
            $route . '.reset.password',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->id,
                'hash' => sha1($this->email),
            ]
        );
    }

    public function getBexioEmployeeRecord(): ?BexioEmployee
    {
        if ($this->bexioEmployee instanceof BexioEmployee) {
            return $this->bexioEmployee;
        }

        if (!$this->wildixin_response) {
            return null;
        }

        $response = json_decode($this->wildixin_response, true);
        $officePhoneNumber = $response['officePhone'] ?? null;
        $mobilePhoneNumber = $response['mobilePhone'] ?? null;
        $faxNumber = $response['faxNumber'] ?? null;
        // dump($officePhoneNumber);
        // dump($mobilePhoneNumber);
        // dd($faxNumber);

        if ($officePhoneNumber) {
            return $this->bexioEmployee = BexioEmployee::where(function ($query) use ($officePhoneNumber) {
                $query->where('phone_number', 'like', $officePhoneNumber);
                $query->orWhere('mobile_number', 'like', $officePhoneNumber);
                $query->orWhere('fax_number', 'like', $officePhoneNumber);
            })->first();
        }

        if ($mobilePhoneNumber) {
            return $this->bexioEmployee = BexioEmployee::where(function ($query) use ($mobilePhoneNumber) {
                $query->where('phone_number', 'like', $mobilePhoneNumber);
                $query->orWhere('mobile_number', 'like', $mobilePhoneNumber);
                $query->orWhere('fax_number', 'like', $mobilePhoneNumber);
            })->first();
        }

        if ($faxNumber) {
            return $this->bexioEmployee = BexioEmployee::where(function ($query) use ($faxNumber) {
                $query->where('phone_number', 'like', $faxNumber);
                $query->orWhere('mobile_number', 'like', $faxNumber);
                $query->orWhere('fax_number', 'like', $faxNumber);
            })->first();
        }

        return null;
    }

    public function clockoDoUserId(): ?int
    {
        return $this->clockodo_emp_id;
    }

    public static function generateTeleApiToken($userId): ?self
    {
        $user = self::find($userId);

        if (!$user) {
            return null;
        }

        if (!$user->tele_api_token) {
            do {
                $token = Str::random(60);
            } while (self::where('tele_api_token', $token)->exists());

            $user->tele_api_token = $token;

            if (!$user->save()) {
                return null;
            }
        }

        return $user;
    }
}
