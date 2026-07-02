<?php

namespace App\Actions\Attendance;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\DB;

class CheckoutAttendanceAction
{
    use AsAction;

    public function handle(User $user, array $data = []): Attendance
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

        // Check if currently on break - MUST prevent Clock Out
        $lastLog = $attendance->logs()
            ->orderBy('action_time', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLog && $lastLog->action_type === 'break_start') {
            throw ValidationException::withMessages([
                'attendance' => 'Please complete Break Out before Clock Out.',
            ]);
        }

        return DB::transaction(function () use ($attendance, $user, $data) {
            $now = now();
            $attendance->check_out = $now;

            if (!empty($data['notes'])) {
                $attendance->notes = $data['notes'];
            }

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

            $totalSeconds = strtotime($now) - strtotime($attendance->check_in);
            $totalWorkSeconds = max(0, $totalSeconds - $totalBreakSeconds);

            $attendance->total_minutes = max(0, round($totalSeconds / 60));
            $attendance->total_work_minutes = max(0, round($totalWorkSeconds / 60));
            $attendance->total_break_minutes = max(0, round($totalBreakSeconds / 60));
            
            // standard shift is 8 hours (480 minutes)
            $attendance->overtime_minutes = max(0, $attendance->total_work_minutes - 480);
            $attendance->save();

            // Create work log if project_title is provided
            if (!empty($data['project_title']) && $user->employee) {
                $employeeWork = \App\Models\EmployeeWork::create([
                    'employee_id' => $user->employee->id,
                    'attendance_id' => $attendance->id,
                    'date' => today(),
                    'project_title' => $data['project_title'],
                    'description' => $data['description'] ?? null,
                ]);

                if (!empty($data['work_images'])) {
                    foreach ($data['work_images'] as $image) {
                        $employeeWork->addMedia($image)->toMediaCollection('work_images');
                    }
                }
            }

            return $attendance;
        });
    }
}
