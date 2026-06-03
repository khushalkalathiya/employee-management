<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'designation_id',
        'employee_code',
        'date_of_birth',
        'joining_date',
        'probation_end_date',
        'salary',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
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

}
