<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class ClockInAttendanceAction
{
    use AsAction;

    public function handle(User $user, ?string $lateReason = null): Attendance
    {
        $openAttendance = Attendance::query()
            ->where('user_id', $user->id)
            ->where('check_out', null)
            ->latest('id')
            ->first();

        if ($openAttendance) {
            throw ValidationException::withMessages([
                'attendance' => 'You are already clocked in. Please clock out first.',
            ]);
        }

        $attendance = Attendance::create([
            'user_id'         => $user->id,
            'attendance_date' => today(),
            'check_in'        => now(),
            'status'          => 'present',
            'notes'           => $lateReason ? 'Late reason: ' . $lateReason : null,
        ]);

        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'action_type'   => 'clock_in',
            'action_time'   => now(),
        ]);

        return $attendance;
    }
}