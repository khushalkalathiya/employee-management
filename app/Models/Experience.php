<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'employee_id',
        'company_name',
        'designation',
        'location',
        'start_date',
        'end_date',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
