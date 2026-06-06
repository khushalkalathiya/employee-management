<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_month',
        'working_days',
        'present_days',
        'leave_days',
        'per_day_salary',
        'earned_salary',
        'pf_amount',
        'other_deductions',
        'hold_amount',
        'final_salary',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
