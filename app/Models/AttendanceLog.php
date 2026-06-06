<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'attendance_id',
        'action_type',
        'action_time',
        'notes',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
