<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BexioEmployee extends Model
{
    use HasFactory;

    public $fillable = [
        'emp_id',
        'name',
        'phone_number',
        'mobile_number',
        'contact_type',
        'first_name',
        'last_name',
        'fax_number',
        'email',
        'city',
        'postal_code',
        'bexio_country_id',
        'clockodo_emp_id',
        'clockodo_response',
        'bexio_response',
    ];

    public const CONTACT_TYPE_COMPANY = 1;
    public const CONTACT_TYPE_EMPLOYEE = 2;

    public static function getContactTypes(): array
    {
        return [
            self::CONTACT_TYPE_COMPANY,
            self::CONTACT_TYPE_EMPLOYEE,
        ];
    }

    public static function getContactTypeList(): array
    {
        return [
            self::CONTACT_TYPE_COMPANY => __('Company'),
            self::CONTACT_TYPE_EMPLOYEE => __('Employee'),
        ];
    }

    public static function getContactType(int $contactType): ?string
    {
        return self::getContactTypeList()[$contactType] ?? null;
    }

    public function companies(): HasManyThrough
    {
        return $this->hasManyThrough(
            self::class,
            BexioEmployeeHasCompany::class,
            'bexio_employee_id',
            'emp_id',
            'emp_id',
            'bexio_company_id'
        )->where('contact_type', self::CONTACT_TYPE_COMPANY);
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            self::class,
            BexioEmployeeHasCompany::class,
            'bexio_company_id',
            'emp_id',
            'emp_id',
            'bexio_employee_id'
        )->where('contact_type', self::CONTACT_TYPE_EMPLOYEE);
    }
}
