<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class BreakStartAttendanceAction
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
                'attendance' => 'You must clock in first before starting a break.',
            ]);
        }

        // Check if already on break
        $lastLog = $attendance->logs()
            ->orderBy('action_time', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLog && $lastLog->action_type === 'break_start') {
            throw ValidationException::withMessages([
                'attendance' => 'You are already on break.',
            ]);
        }

        return AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'action_type' => 'break_start',
            'action_time' => now(),
        ]);
    }
}
