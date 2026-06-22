<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class BreakEndAttendanceAction
{
    use AsAction;

    public function handle(User $user): AttendanceLog
    {
        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereNull('check_out')
            ->latest('id')
            ->first();

        if (!$attendance) {
            throw ValidationException::withMessages([
                'attendance' => 'You are not clocked in.',
            ]);
        }

        // Check if currently on break
        $lastLog = $attendance->logs()
            ->orderBy('action_time', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastLog || $lastLog->action_type !== 'break_start') {
            throw ValidationException::withMessages([
                'attendance' => 'You are not currently on a break.',
            ]);
        }

        return AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'action_type' => 'break_end',
            'action_time' => now(),
        ]);
    }
}
