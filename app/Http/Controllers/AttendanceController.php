<?php

namespace App\Http\Controllers;

use App\Actions\Attendance\ClockInAttendanceAction;
use App\Actions\Attendance\CheckoutAttendanceAction;
use App\Actions\Attendance\BreakStartAttendanceAction;
use App\Actions\Attendance\BreakEndAttendanceAction;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    public function index()
    {
        // Component-level permission enforcement; allow both own and view users
        abort_unless(
            auth()->user()?->can('attendance.own') || auth()->user()?->can('attendance.view'),
            403
        );

        return view('attendance.index');
    }

    /**
     * Show the edit page for an attendance record (saves old_data snapshot).
     */
    public function edit(Attendance $attendance)
    {
        // Employees can only edit their own attendance if they have attendance.own
        if (auth()->user()?->can('attendance.own')) {
            abort_unless($attendance->user_id === auth()->id(), 403);
        }

        $attendance->load(['user', 'user.employee', 'logs']);

        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update an attendance record and store a snapshot of old data.
     */
    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        // Employees can only update their own attendance
        if (auth()->user()?->can('attendance.own')) {
            abort_unless($attendance->user_id === auth()->id(), 403);
        }

        $validated = $request->validate([
            'check_in'           => ['nullable', 'date_format:Y-m-d H:i:s'],
            'check_out'          => ['nullable', 'date_format:Y-m-d H:i:s'],
            'status'             => ['required', 'in:present,absent,leave,holiday,half_day,week_off'],
            'notes'              => ['nullable', 'string', 'max:1000'],
            'total_work_minutes' => ['nullable', 'integer', 'min:0'],
            'total_break_minutes'=> ['nullable', 'integer', 'min:0'],
            'overtime_minutes'   => ['nullable', 'integer', 'min:0'],
        ]);

        // Snapshot old data before update
        $oldData = $attendance->only([
            'check_in', 'check_out', 'status', 'notes',
            'total_work_minutes', 'total_break_minutes', 'overtime_minutes',
        ]);
        $oldData['updated_by'] = auth()->id();
        $oldData['updated_at'] = now()->toDateTimeString();
        $oldData['previous']   = $attendance->old_data ?? [];

        $attendance->update(array_merge($validated, ['old_data' => $oldData]));

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Delete an attendance record.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        // Employees can only delete their own attendance if they have attendance.own
        if (auth()->user()?->can('attendance.own')) {
            abort_unless($attendance->user_id === auth()->id(), 403);
        }

        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }

    public function currentStatus(): JsonResponse
    {
        try {
            // ── Load today's schedule config ────────────────────────────────
            $today = strtolower(now()->format('l')); // e.g. "monday"

            $scheduleKeys = [
                'late_allowance_minutes',
                'early_clock_in_minutes',
                'break_notification_before_seconds',
                'early_break_out_minutes',
                "{$today}_working",
                "{$today}_start_time",
                "{$today}_end_time",
                "{$today}_break_enabled",
                "{$today}_break_start",
                "{$today}_break_end",
                "{$today}_break_time",
            ];

            $raw = \App\Models\Setting::whereIn('key', $scheduleKeys)
                ->pluck('value', 'key')
                ->toArray();

            $schedule = [
                'today'                              => $today,
                'is_working_day'                     => (bool)  ($raw["{$today}_working"]      ?? false),
                'start_time'                         =>          $raw["{$today}_start_time"]    ?? null,
                'end_time'                           =>          $raw["{$today}_end_time"]      ?? null,
                'break_enabled'                      => (bool)  ($raw["{$today}_break_enabled"] ?? false),
                'break_start'                        =>          $raw["{$today}_break_start"]   ?? null,
                'break_end'                          =>          $raw["{$today}_break_end"]     ?? null,
                'break_time'                         => (int)   ($raw["{$today}_break_time"]    ?? 0),
                'late_allowance_minutes'             => (int)   ($raw['late_allowance_minutes']            ?? 10),
                'early_clock_in_minutes'             => (int)   ($raw['early_clock_in_minutes']            ?? 15),
                'break_notification_before_seconds'  => (int)   ($raw['break_notification_before_seconds'] ?? 60),
                'early_break_out_minutes'            => (int)   ($raw['early_break_out_minutes']            ?? 0),
            ];
            // ───────────────────────────────────────────────────────────────

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
            $sortedLogs = $allLogs->sortBy(function ($log) {
                return $log->action_time . '_' . $log->id;
            })->values();

            $mappedLogs = $sortedLogs->map(fn ($log) => [
                'id'           => $log->id,
                'type'         => $log->action_type,
                'time'         => $log->action_time,
                'display_time' => date('h:i:s A', strtotime($log->action_time)),
            ]);

            if (! $activeAttendance) {
                return response()->json([
                    'success' => true,
                    'data'    => [
                        'is_clocked_in'   => false,
                        'is_on_break'     => false,
                        'working_seconds' => 0,
                        'break_seconds'   => 0,
                        'logs'            => $mappedLogs,
                        'schedule'        => $schedule,
                    ],
                ]);
            }

            $activeLogs = $activeAttendance->logs
                ->sortBy('action_time')
                ->values();

            $breakSeconds      = 0;
            $currentBreakStart = null;
            $isOnBreak         = false;

            foreach ($activeLogs as $log) {
                if ($log->action_type === 'break_start') {
                    $currentBreakStart = $log->action_time;
                    $isOnBreak         = true;
                }

                if ($log->action_type === 'break_end' && $currentBreakStart) {
                    $breakSeconds     += strtotime($log->action_time) - strtotime($currentBreakStart);
                    $currentBreakStart = null;
                    $isOnBreak         = false;
                }
            }

            if ($isOnBreak && $currentBreakStart) {
                $breakSeconds += now()->timestamp - strtotime($currentBreakStart);
            }

            $workingSeconds = now()->timestamp - strtotime($activeAttendance->check_in) - $breakSeconds;

            return response()->json([
                'success' => true,
                'data'    => [
                    'attendance_id'       => $activeAttendance->id,
                    'attendance_date'     => $activeAttendance->attendance_date,
                    'check_in'            => $activeAttendance->check_in,
                    'check_out'           => $activeAttendance->check_out,
                    'clock_in_date'       => \Carbon\Carbon::parse($activeAttendance->check_in)->format('Y-m-d'),
                    'clock_in_time'       => \Carbon\Carbon::parse($activeAttendance->check_in)->format('h:i:s A'),
                    'is_clocked_in'       => true,
                    'is_on_break'         => $isOnBreak,
                    'working_seconds'     => max(0, $workingSeconds),
                    'break_seconds'       => $breakSeconds,
                    'current_break_start' => $currentBreakStart,
                    'logs'                => $mappedLogs,
                    'schedule'            => $schedule,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }

    public function checkIn(Request $request, ClockInAttendanceAction $action): JsonResponse
    {
        try {
            $validated = $request->validate([
                'late_reason' => ['nullable', 'string', 'min:5', 'max:1000'],
            ]);

            $action->handle(auth_user(), $validated['late_reason'] ?? null);

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
