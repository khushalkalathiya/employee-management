<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyInformation extends Model
{
    protected $table = 'family_information';

    protected $fillable = [
        'employee_id',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'spouse_name',
        'spouse_phone',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
