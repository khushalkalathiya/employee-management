<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeAttendance extends Component
{
    use WithPagination;

    public string $dateFrom;
    public string $dateTo;
    public string $filterStatus = '';
    public int    $perPage = 15;

    // Analytics
    public string $totalWorkHours  = '0h 0m';
    public string $overtimeHours   = '0h 0m';
    public string $pendingHours    = '0h 0m';
    public int    $leaveDays       = 0;

    // View Modal
    public ?int   $viewingAttendanceId = null;
    public array  $viewingLogs         = [];
    public ?array $viewingRecord       = null;

    // Delete confirmation
    public ?int   $deletingId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('attendance.own'), 403);

        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->endOfMonth()->format('Y-m-d');

        $this->computeAnalytics();
    }

    public function updatedDateFrom(): void { $this->resetPage(); $this->computeAnalytics(); }
    public function updatedDateTo(): void   { $this->resetPage(); $this->computeAnalytics(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    #[On('refresh-table')]
    public function refresh(): void
    {
        $this->resetPage();
        $this->computeAnalytics();
    }

    // ── View Modal ───────────────────────────────────────────────
    public function viewAttendance(int $id): void
    {
        $record = Attendance::with('logs')->findOrFail($id);
        abort_unless($record->user_id === auth()->id(), 403);

        $this->viewingAttendanceId = $id;
        $this->viewingRecord = [
            'date'         => $record->attendance_date,
            'check_in'     => $record->check_in,
            'check_out'    => $record->check_out,
            'status'       => $record->status,
            'work_minutes' => $record->total_work_minutes,
            'break_minutes'=> $record->total_break_minutes,
            'overtime'     => $record->overtime_minutes,
            'notes'        => $record->notes,
        ];

        $this->viewingLogs = $record->logs
            ->sortBy('action_time')
            ->values()
            ->map(fn ($log) => [
                'type' => $log->action_type,
                'time' => $log->action_time,
            ])->toArray();

        $this->dispatch('open-view-modal');
    }

    public function closeViewModal(): void
    {
        $this->viewingAttendanceId = null;
        $this->viewingRecord       = null;
        $this->viewingLogs         = [];
    }

    // ── Delete ───────────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->can('attendance.delete'), 403);
        $this->deletingId = $id;
        $this->dispatch('open-delete-confirm');
    }

    public function deleteAttendance(): void
    {
        abort_unless(auth()->user()?->can('attendance.delete'), 403);

        if ($this->deletingId) {
            $record = Attendance::findOrFail($this->deletingId);
            abort_unless($record->user_id === auth()->id(), 403);
            $record->delete();
            $this->deletingId = null;
            $this->computeAnalytics();
            $this->dispatch('attendance-deleted');
            session()->flash('success', 'Attendance record deleted.');
        }
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    // ── Analytics ────────────────────────────────────────────────
    private function computeAnalytics(): void
    {
        $userId = auth()->id();
        $from   = Carbon::parse($this->dateFrom)->startOfDay();
        $to     = Carbon::parse($this->dateTo)->endOfDay();

        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->get();

        $settings = collect(Cache::remember('settings', 600, fn () =>
            Setting::pluck('value', 'key')->toArray()
        ))->toArray();

        $holidays = Holiday::query()
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start', [$from, $to])
                    ->orWhereBetween('end', [$from, $to])
                    ->orWhere(fn ($q2) => $q2->where('start', '<=', $from)->where('end', '>=', $to));
            })->get();

        $holidayDates = [];
        foreach ($holidays as $h) {
            foreach (CarbonPeriod::create(Carbon::parse($h->start)->toDateString(), Carbon::parse($h->end)->toDateString()) as $d) {
                $holidayDates[] = $d->toDateString();
            }
        }
        $holidayDates = array_unique($holidayDates);

        $approvedLeaves = LeaveRequest::query()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->where(fn ($q) => $q->whereBetween('start_datetime', [$from, $to])->orWhereBetween('end_datetime', [$from, $to]))
            ->get();

        $leaveDates = [];
        foreach ($approvedLeaves as $leave) {
            foreach (CarbonPeriod::create(Carbon::parse($leave->start_datetime)->toDateString(), Carbon::parse($leave->end_datetime)->toDateString()) as $d) {
                $leaveDates[] = $d->toDateString();
            }
        }
        $leaveDates = array_unique($leaveDates);

        $period          = CarbonPeriod::create($from->toDateString(), $to->toDateString());
        $requiredMinutes = 0;

        foreach ($period as $date) {
            if ($date->isAfter(now())) break;
            $dayName   = strtolower($date->format('l'));
            $isWorking = filter_var($settings["{$dayName}_working"] ?? false, FILTER_VALIDATE_BOOLEAN);
            if (! $isWorking || in_array($date->toDateString(), $holidayDates)) continue;
            $requiredMinutes += (int) ($settings["{$dayName}_required_minutes"] ?? 480);
        }

        $totalWorkMinutes     = $attendances->sum('total_work_minutes');
        $totalOvertimeMinutes = $attendances->sum('overtime_minutes');

        // Leave days = attendance records with leave status + leave request days not in attendance
        $leaveDaysFromAttendance = $attendances->where('status', 'leave')->count();
        $attendanceDates = $attendances->pluck('attendance_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())->toArray();
        $extraLeave = count(array_diff($leaveDates, $attendanceDates));

        $this->leaveDays     = $leaveDaysFromAttendance + $extraLeave;
        $this->totalWorkHours= $this->minutesToHM($totalWorkMinutes);
        $this->overtimeHours = $this->minutesToHM($totalOvertimeMinutes);
        $this->pendingHours  = $this->minutesToHM(max(0, $requiredMinutes - $totalWorkMinutes));
    }

    private function minutesToHM(int $minutes): string
    {
        return intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'm';
    }

    public function render()
    {
        abort_unless(auth()->user()?->can('attendance.own'), 403);

        $userId = auth()->id();
        $from   = Carbon::parse($this->dateFrom)->startOfDay();
        $to     = Carbon::parse($this->dateTo)->endOfDay();

        $query = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('attendance_date');

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        $attendances = $query->paginate($this->perPage);

        return view('livewire.attendance.employee-attendance', [
            'attendances' => $attendances,
        ]);
    }
}
