<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class AttendanceControlCard extends Component
{
    public bool $isClockedIn = false;
    public bool $isOnBreak = false;
    public ?string $clockInTime = null;
    public ?string $clockInDate = null;
    public int $workingSeconds = 0;
    public int $breakSeconds = 0;
    public array $todayLogs = [];
    public ?int $attendanceId = null;

    // Schedule data passed to blade for client-side clock-in window logic
    public array $scheduleConfig = [];

    public function mount(): void
    {
        $this->loadStatus();
        $this->loadScheduleConfig();
    }

    /**
     * Load today's schedule rules into a JS-friendly structure.
     * This is passed to Alpine.js to drive enable/disable of the Clock In button
     * and late-reason enforcement — all without extra AJAX calls.
     */
    public function loadScheduleConfig(): void
    {
        $today = strtolower(now()->format('l')); // e.g. "monday"

        $keys = [
            'timing_mode',
            'late_allowance_minutes',
            'early_clock_in_minutes',
            "{$today}_working",
            "{$today}_start_time",
            "{$today}_end_time",
        ];

        $raw = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        $this->scheduleConfig = [
            'today'                       => $today,
            'timing_mode'                 => $raw['timing_mode'] ?? 'fixed',
            'is_working_day'              => (bool) ($raw["{$today}_working"] ?? false),
            'start_time'                  => $raw["{$today}_start_time"] ?? null,   // "g:i A" format, e.g. "9:00 AM"
            'end_time'                    => $raw["{$today}_end_time"] ?? null,
            'late_allowance_minutes'      => (int) ($raw['late_allowance_minutes'] ?? 10),
            'early_clock_in_minutes'      => (int) ($raw['early_clock_in_minutes'] ?? 15),
        ];
    }

    #[On('refresh-control-card')]
    public function loadStatus(): void
    {
        $userId = authId();

        $activeAttendance = Attendance::query()
            ->with('logs')
            ->where('user_id', $userId)
            ->whereNull('check_out')
            ->latest('id')
            ->first();

        // All today's attendances for complete log
        $todayAttendances = Attendance::query()
            ->with('logs')
            ->where('user_id', $userId)
            ->where('attendance_date', today())
            ->orderBy('id', 'asc')
            ->get();

        $allLogs = collect();
        foreach ($todayAttendances as $att) {
            $allLogs = $allLogs->merge($att->logs);
        }

        $sortedLogs = $allLogs->sortBy(fn($log) => $log->action_time . '_' . $log->id)->values();
        $this->todayLogs = $sortedLogs->map(fn($log) => [
            'id'           => $log->id,
            'type'         => $log->action_type,
            'display_time' => Carbon::parse($log->action_time)->format('h:i:s A'),
        ])->toArray();

        if (! $activeAttendance) {
            $this->isClockedIn  = false;
            $this->isOnBreak    = false;
            $this->workingSeconds = 0;
            $this->breakSeconds   = 0;
            $this->clockInTime  = null;
            $this->clockInDate  = null;
            $this->attendanceId = null;
            return;
        }

        $this->attendanceId = $activeAttendance->id;
        $this->isClockedIn  = true;
        $this->clockInTime  = Carbon::parse($activeAttendance->check_in)->format('h:i A');
        $this->clockInDate  = Carbon::parse($activeAttendance->attendance_date)->format('l, d F Y');

        $activeLogs = $activeAttendance->logs->sortBy('action_time')->values();
        $breakSecs  = 0;
        $breakStart = null;
        $onBreak    = false;

        foreach ($activeLogs as $log) {
            if ($log->action_type === 'break_start') {
                $breakStart = $log->action_time;
                $onBreak    = true;
            } elseif ($log->action_type === 'break_end' && $breakStart) {
                $breakSecs += strtotime($log->action_time) - strtotime($breakStart);
                $breakStart = null;
                $onBreak    = false;
            }
        }

        if ($onBreak && $breakStart) {
            $breakSecs += now()->timestamp - strtotime($breakStart);
        }

        $this->isOnBreak      = $onBreak;
        $this->breakSeconds   = $breakSecs;
        $this->workingSeconds = max(0, now()->timestamp - strtotime($activeAttendance->check_in) - $breakSecs);
    }

    public function render()
    {
        return view('livewire.attendance.attendance-control-card');
    }
}
