<?php

namespace App\Http\Controllers;

use App\Actions\Attendance\ClockInAttendanceAction;
use App\Actions\Attendance\CheckoutAttendanceAction;
use App\Actions\Attendance\BreakStartAttendanceAction;
use App\Actions\Attendance\BreakEndAttendanceAction;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        // $attendances = \App\Models\Attendance::where('employee_id', $employee->id)
        //     ->whereMonth('attendance_date', now()->month)
        //     ->whereYear('attendance_date', now()->year)
        //     ->with('logs')
        //     ->orderBy('attendance_date', 'desc')
        //     ->get();

        // // Calculate monthly stats
        // $totalDaysInMonth = now()->daysInMonth;
        // $presentDays = $attendances->where('status', 'present')->count();
        // $absentDays = $totalDaysInMonth - $presentDays - $attendances->where('status', 'leave')->count();
        // $leaveDays = $attendances->where('status', 'leave')->count();
        // $halfDays = $attendances->where('total_minutes', '>', 0)->where('total_minutes', '<', 8 * 60)->count();
        // $lateCount = $attendances->where('late', true)->count();
        // $totalHours = $attendances->sum('total_work_minutes') / 60;

        // return view('attendance.index', compact('attendances', 'presentDays', 'absentDays', 'leaveDays', 'halfDays', 'lateCount', 'totalHours', 'totalDaysInMonth'));
        return view('attendance.index');
    }

    public function currentStatus(): JsonResponse
    {
        try {
            $activeAttendance = Attendance::query()
                ->with('logs')
                ->where('user_id', authId())
                ->whereNull('check_out')
                ->latest('id')
                ->first();

            // Get all attendances for today
            $todayAttendances = Attendance::query()
                ->with('logs')
                ->where('user_id', authId())
                ->where('attendance_date', today())
                ->orderBy('id', 'asc')
                ->get();

            // Collect all logs for today
            $allLogs = collect();
            foreach ($todayAttendances as $todayAttendance) {
                $allLogs = $allLogs->merge($todayAttendance->logs);
            }
            $sortedLogs = $allLogs->sortBy(function($log) {
                return $log->action_time . '_' . $log->id;
            })->values();

            $mappedLogs = $sortedLogs->map(fn ($log) => [
                'id' => $log->id,
                'type' => $log->action_type,
                'time' => $log->action_time,
                'display_time' => date('h:i:s A', strtotime($log->action_time)),
            ]);

            if (! $activeAttendance) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'is_clocked_in' => false,
                        'is_on_break' => false,
                        'working_seconds' => 0,
                        'break_seconds' => 0,
                        'logs' => $mappedLogs,
                    ],
                ]);
            }

            // Calculate active session break and working seconds
            $activeLogs = $activeAttendance->logs
                ->sortBy('action_time')
                ->values();

            $breakSeconds = 0;
            $currentBreakStart = null;
            $isOnBreak = false;

            foreach ($activeLogs as $log) {
                if ($log->action_type === 'break_start') {
                    $currentBreakStart = $log->action_time;
                    $isOnBreak = true;
                }

                if ($log->action_type === 'break_end' && $currentBreakStart) {
                    $breakSeconds += strtotime($log->action_time) - strtotime($currentBreakStart);
                    $currentBreakStart = null;
                    $isOnBreak = false;
                }
            }

            if ($isOnBreak && $currentBreakStart) {
                $breakSeconds += now()->timestamp - strtotime($currentBreakStart);
            }

            $workingSeconds = now()->timestamp - strtotime($activeAttendance->check_in) - $breakSeconds;

            return response()->json([
                'success' => true,
                'data' => [
                    'attendance_id' => $activeAttendance->id,
                    'attendance_date' => $activeAttendance->attendance_date,
                    'check_in' => $activeAttendance->check_in,
                    'check_out' => $activeAttendance->check_out,
                    'clock_in_date' => \Carbon\Carbon::parse($activeAttendance->check_in)->format('Y-m-d'),
                    'clock_in_time' => \Carbon\Carbon::parse($activeAttendance->check_in)->format('h:i:s A'),
                    'is_clocked_in' => true,
                    'is_on_break' => $isOnBreak,
                    'working_seconds' => max(0, $workingSeconds),
                    'break_seconds' => $breakSeconds,
                    'current_break_start' => $currentBreakStart,
                    'logs' => $mappedLogs,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function checkIn(ClockInAttendanceAction $action): JsonResponse
    {
        try {
            $action->handle(auth_user());

            return response()->json([
                'success' => true,
                'message' => 'Clock in successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkOut(CheckoutAttendanceAction $action): JsonResponse
    {
        try {
            $action->handle(auth_user());

            return response()->json([
                'success' => true,
                'message' => 'Clock out successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function breakStart(BreakStartAttendanceAction $action): JsonResponse
    {
        try {
            $action->handle(auth_user());

            return response()->json([
                'success' => true,
                'message' => 'Break started successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function breakEnd(BreakEndAttendanceAction $action): JsonResponse
    {
        try {
            $action->handle(auth_user());

            return response()->json([
                'success' => true,
                'message' => 'Break ended successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
