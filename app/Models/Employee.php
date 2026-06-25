<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'designation_id',
        'gender', // male, female, other
        'date_of_birth',
        'marital_status', // single, married, divorced, widowed
        'alternate_phone',
        'joining_date',
        'probation_end_date',
        'employment_type', // permanent, contract, intern, freelancer
        'reporting_manager_id', // user_id
        'status', // active, probation, notice, resigned, terminated
        'current_salary',
        'is_hourly', // true = paid per hour, false = monthly
        'address',
        'city',
        'state',
        'country',
        'postal_code',
    ];

    protected $casts = [
        'is_hourly'      => 'boolean',
        'current_salary' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function familyInformation()
    {
        return $this->hasOne(FamilyInformation::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}

