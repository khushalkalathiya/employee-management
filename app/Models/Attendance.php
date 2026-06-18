<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in',
        'check_out',
        'total_minutes',
        'total_work_minutes',
        'total_break_minutes',
        'overtime_minutes',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
