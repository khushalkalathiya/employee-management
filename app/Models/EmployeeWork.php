<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmployeeWork extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'employee_works';

    protected $fillable = [
        'employee_id',
        'attendance_id',
        'date',
        'project_title',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
