<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;

class ClockInAttendanceAction
{
    public function execute(User $user): Attendance
    {
        $today = today();

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'attendance_date' => $today,
            ],
            [
                'check_in' => now()->format('H:i:s'),
                'status' => 'present',
            ]
        );

        if (! $attendance->check_in) {
            $attendance->update([
                'check_in' => now()->format('H:i:s'),
                'status' => 'present',
            ]);
        }

        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'action_type' => 'clock_in',
            'action_time' => now(),
        ]);

        return $attendance;
    }
}