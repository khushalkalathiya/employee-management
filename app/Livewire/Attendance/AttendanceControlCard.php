<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
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

    public function mount(): void
    {
        $this->loadStatus();
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
