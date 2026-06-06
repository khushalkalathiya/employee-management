<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';

    protected $fillable = [
        'employee_id',
        'qualification',
        'institute_name',
        'board_university',
        'passing_year',
        'percentage_grade',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
