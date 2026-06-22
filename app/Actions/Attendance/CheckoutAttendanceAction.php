<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckoutAttendanceAction
{
    use AsAction;

    public function handle(User $user): Attendance
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

        $now = now();
        $attendance->check_out = $now;

        // Create checkout log
        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'action_type' => 'clock_out',
            'action_time' => $now,
        ]);

        // Get logs in order of action_time
        $logs = $attendance->logs()
            ->orderBy('action_time', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalBreakSeconds = 0;
        $currentBreakStart = null;
        $isOnBreak = false;

        foreach ($logs as $log) {
            if ($log->action_type === 'break_start') {
                $currentBreakStart = $log->action_time;
                $isOnBreak = true;
            } elseif ($log->action_type === 'break_end' && $currentBreakStart) {
                $totalBreakSeconds += strtotime($log->action_time) - strtotime($currentBreakStart);
                $currentBreakStart = null;
                $isOnBreak = false;
            }
        }

        // If clocked out while on break, automatically end the break log
        if ($isOnBreak && $currentBreakStart) {
            $totalBreakSeconds += strtotime($now) - strtotime($currentBreakStart);
            AttendanceLog::create([
                'attendance_id' => $attendance->id,
                'action_type' => 'break_end',
                'action_time' => $now,
            ]);
        }

        $totalSeconds = strtotime($now) - strtotime($attendance->check_in);
        $totalWorkSeconds = max(0, $totalSeconds - $totalBreakSeconds);

        $attendance->total_minutes = max(0, round($totalSeconds / 60));
        $attendance->total_work_minutes = max(0, round($totalWorkSeconds / 60));
        $attendance->total_break_minutes = max(0, round($totalBreakSeconds / 60));
        
        // standard shift is 8 hours (480 minutes)
        $attendance->overtime_minutes = max(0, $attendance->total_work_minutes - 480);
        $attendance->save();

        return $attendance;
    }
}
