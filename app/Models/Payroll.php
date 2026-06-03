<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'salary_month',
    ];
}
