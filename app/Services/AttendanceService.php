<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AttendanceService
{
    /**
     * Automatically log Break In for the user after validation.
     */
    public function autoBreakIn(User $user): void
    {
        $employee = $user->employee;
        if (!$employee || !$employee->auto_break_enabled) {
            throw new \Exception("Auto break is not enabled for this employee.");
        }

        $dayName = strtolower(now()->format('l'));
        $working = Setting::where('key', "{$dayName}_working")->value('value');
        $breakEnabled = Setting::where('key', "{$dayName}_break_enabled")->value('value');

        if (!filter_var($working, FILTER_VALIDATE_BOOLEAN)) {
            throw new \Exception("Today is not a working day.");
        }

        if (!filter_var($breakEnabled, FILTER_VALIDATE_BOOLEAN)) {
            throw new \Exception("Break is not enabled for today.");
        }

        // Find active attendance today
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->whereNull('check_out')
            ->latest('id')
            ->first();

        if (!$attendance) {
            throw new \Exception("Employee is not clocked in.");
        }

        $breakInTimeStr = Setting::where('key', "{$dayName}_break_start")->value('value');
        if (!$breakInTimeStr) {
            throw new \Exception("Break start time is not configured.");
        }

        $breakInTime = Carbon::parse(today()->toDateString() . ' ' . $breakInTimeStr);
        $checkInTime = Carbon::parse($attendance->check_in);

        if ($checkInTime->greaterThanOrEqualTo($breakInTime)) {
            throw new \Exception("Check-in time is after the configured break start time.");
        }

        // Prevent duplicate Break In
        $hasBreakStart = $attendance->logs()->where('action_type', 'break_start')->exists();
        if ($hasBreakStart) {
            throw new \Exception("Break In already exists for today.");
        }

        DB::transaction(function () use ($attendance, $breakInTime) {
            // Re-check inside transaction to prevent race conditions / duplicate requests
            $stillNoBreakStart = !$attendance->logs()->where('action_type', 'break_start')->exists();
            if (!$stillNoBreakStart) {
                throw new \Exception("Break In already exists for today.");
            }

            AttendanceLog::create([
                'attendance_id' => $attendance->id,
                'action_type' => 'break_start',
                'action_time' => $breakInTime,
                'notes' => 'Automatic break start (Browser API)',
            ]);
        });
    }

    /**
     * Automatically log Break Out for the user after validation.
     */
    public function autoBreakOut(User $user): void
    {
        $employee = $user->employee;
        if (!$employee || !$employee->auto_break_enabled) {
            throw new \Exception("Auto break is not enabled for this employee.");
        }

        $dayName = strtolower(now()->format('l'));
        $working = Setting::where('key', "{$dayName}_working")->value('value');
        $breakEnabled = Setting::where('key', "{$dayName}_break_enabled")->value('value');

        if (!filter_var($working, FILTER_VALIDATE_BOOLEAN)) {
            throw new \Exception("Today is not a working day.");
        }

        if (!filter_var($breakEnabled, FILTER_VALIDATE_BOOLEAN)) {
            throw new \Exception("Break is not enabled for today.");
        }

        // Find active attendance today
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->whereNull('check_out')
            ->latest('id')
            ->first();

        if (!$attendance) {
            throw new \Exception("Employee is not clocked in.");
        }

        $breakOutTimeStr = Setting::where('key', "{$dayName}_break_end")->value('value');
        if (!$breakOutTimeStr) {
            throw new \Exception("Break end time is not configured.");
        }

        $breakOutTime = Carbon::parse(today()->toDateString() . ' ' . $breakOutTimeStr);

        $lastLog = $attendance->logs()
            ->orderBy('action_time', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastLog || $lastLog->action_type !== 'break_start') {
            throw new \Exception("Employee is not currently on break.");
        }

        $breakStartVal = Carbon::parse($lastLog->action_time);
        if ($breakStartVal->greaterThanOrEqualTo($breakOutTime)) {
            throw new \Exception("Break start is after the configured break end time.");
        }

        DB::transaction(function () use ($attendance, $breakOutTime) {
            // Re-check inside transaction
            $lastLogCheck = $attendance->logs()
                ->orderBy('action_time', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            if (!$lastLogCheck || $lastLogCheck->action_type !== 'break_start') {
                throw new \Exception("Employee is not currently on break.");
            }

            AttendanceLog::create([
                'attendance_id' => $attendance->id,
                'action_type' => 'break_end',
                'action_time' => $breakOutTime,
                'notes' => 'Automatic break end (Browser API)',
            ]);
        });
    }
}
