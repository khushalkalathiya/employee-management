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

    public function create()
    {
        return view('attendance.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('attendance.create')) {
            abort(403);
        }

        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employees.create')->with('error', 'Please complete your employee profile first');
        }

        $request->validate([
            'date' => 'required|date|unique:attendances,attendance_date,NULL,id,employee_id,' . $employee->id,
        ]);

        // Check if within working hours
        $workSchedule = $this->getCurrentWorkSchedule();
        $requestTime = \Carbon\Carbon::parse($request->time);

        if (!$requestTime->between($workSchedule['start'], $workSchedule['end'])) {
            return redirect()->back()->with('error', 'Attendance can only be marked within working hours.');
        }

        // Determine status
        $isLate = $requestTime->gt($workSchedule['late_arrival']);
        $status = $isLate ? 'late' : 'present';

        // Create attendance record
        $attendance = \App\Models\Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => $request->date,
            'check_in' => $request->time,
            'status' => $status,
            'late' => $isLate,
        ]);

        // Create log
        \App\Models\AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'log_time' => $request->time,
            'log_type' => 'check_in',
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance marked successfully!');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo('attendance.edit')) {
            abort(403);
        }

        $attendance = \App\Models\Attendance::findOrFail($id);

        // Check ownership
        if ($attendance->employee_id !== auth()->user()->employee_id) {
            abort(403);
        }

        // Calculate duration
        if ($attendance->check_out && $attendance->check_in) {
            $attendance->total_minutes = \Carbon\Carbon::parse($attendance->check_out)->diffInMinutes($attendance->check_in);
            $attendance->total_work_minutes = $attendance->total_minutes;
            $attendance->total_break_minutes = 0;
        }

        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermissionTo('attendance.edit')) {
            abort(403);
        }

        $attendance = \App\Models\Attendance::findOrFail($id);

        if ($attendance->employee_id !== auth()->user()->employee_id) {
            abort(403);
        }

        $validated = $request->validate([
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:500',
        ]);

        $workSchedule = $this->getCurrentWorkSchedule();

        // Update check-in and recalculate status
        $checkInTime = \Carbon\Carbon::parse($validated['check_in']);
        $isLate = $checkInTime->gt($workSchedule['late_arrival']);

        $attendance->fill($validated);
        $attendance->status = $isLate ? 'late' : 'present';
        $attendance->late = $isLate;

        // Calculate duration if check-out is provided
        if (!empty($validated['check_out'])) {
            $checkOutTime = \Carbon\Carbon::parse($validated['check_out']);
            $attendance->total_minutes = $checkOutTime->diffInMinutes($checkInTime);
            $attendance->total_work_minutes = $attendance->total_minutes;
            $attendance->total_break_minutes = 0;

            // Update log
            $log = \App\Models\AttendanceLog::where('attendance_id', $attendance->id)
                ->where('log_type', 'check_out')
                ->first();

            if ($log) {
                $log->update(['log_time' => $validated['check_out']]);
            } else {
                \App\Models\AttendanceLog::create([
                    'attendance_id' => $attendance->id,
                    'log_time' => $validated['check_out'],
                    'log_type' => 'check_out',
                ]);
            }
        }

        $attendance->save();

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('attendance.delete')) {
            abort(403);
        }

        $attendance = \App\Models\Attendance::findOrFail($id);

        if ($attendance->employee_id !== auth()->user()->employee_id) {
            abort(403);
        }

        $attendance->logs()->delete();
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully!');
    }

    public function markLeave(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('leave.create')) {
            abort(403);
        }

        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json(['error' => 'Please complete your employee profile first'], 400);
        }

        $request->validate([
            'date' => 'required|date',
        ]);

        // Check if already present
        $exists = \App\Models\Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', $request->date)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Attendance already marked for this date'], 400);
        }

        // Check if today
        if ($request->date === now()->toDateString()) {
            return response()->json(['error' => 'You can only mark leave for future dates'], 400);
        }

        // Create leave attendance
        \App\Models\Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => $request->date,
            'status' => 'leave',
        ]);

        return response()->json(['success' => 'Leave marked successfully!']);
    }

    public function markAbsent(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('attendance.create')) {
            abort(403);
        }

        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json(['error' => 'Please complete your employee profile first'], 400);
        }

        $request->validate([
            'date' => 'required|date',
        ]);

        $exists = \App\Models\Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', $request->date)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Attendance already marked for this date'], 400);
        }

        \App\Models\Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => $request->date,
            'status' => 'absent',
        ]);

        return response()->json(['success' => 'Absent marked successfully!']);
    }

    /**
     * Get current work schedule
     */
    private function getCurrentWorkSchedule()
    {
        // Get work schedule from settings
        $schedule = \App\Models\Setting::where('key', 'work_schedule')->first();

        if ($schedule) {
            $scheduleData = json_decode($schedule->value, true);

            // Get today's day
            $today = now()->dayOfWeek;
            $dayName = strtolower(now()->format('l'));

            // Find today's schedule
            $todaySchedule = null;
            foreach ($scheduleData as $item) {
                if ($item['day'] == $today || strtolower($item['day']) == $dayName) {
                    $todaySchedule = $item;
                    break;
                }
            }

            if ($todaySchedule && $todaySchedule['status'] == 'workday') {
                return [
                    'start' => \Carbon\Carbon::parse($todaySchedule['start_time']),
                    'end' => \Carbon\Carbon::parse($todaySchedule['end_time']),
                    'late_arrival' => \Carbon\Carbon::parse($todaySchedule['late_arrival_time']),
                    'status' => 'workday',
                ];
            }
        }

        // Default schedule if not found in settings
        return [
            'start' => \Carbon\Carbon::parse('09:00'),
            'end' => \Carbon\Carbon::parse('18:00'),
            'late_arrival' => \Carbon\Carbon::parse('09:15'),
            'status' => 'workday',
        ];
    }

    /**
     * Get attendance for a specific date
     */
    public function getAttendanceForDate(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('attendance.view')) {
            abort(403);
        }

        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json(['error' => 'Employee profile not found'], 404);
        }

        $date = $request->date;

        $attendance = \App\Models\Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', $date)
            ->with('logs')
            ->first();

        return response()->json($attendance);
    }
}
