<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexioEmployeeHasCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'bexio_employee_id',
        'bexio_company_id',
        'contact_relation_id',
    ];

    public function employee()
    {
        return $this->belongsTo(BexioEmployee::class, 'bexio_employee_id', 'emp_id');
    }

    public function company()
    {
        return $this->belongsTo(BexioEmployee::class, 'bexio_company_id', 'emp_id');
    }
}
