<?php

namespace App\Http\Controllers;

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

    public function clockIn(ClockInAttendanceAction $action): JsonResponse {
        $attendance = $action->execute(auth()->user());

        return response()->json([
            'success' => true,
            'message' => 'Clock in successful.',
            'attendance_id' => $attendance->id,
        ]);
    }
}
