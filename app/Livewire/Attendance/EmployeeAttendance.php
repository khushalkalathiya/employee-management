<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
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
    public int $perPage = 15;

    // Analytics
    public int $presentDays    = 0;
    public int $absentDays     = 0;
    public int $leaveDays      = 0;
    public string $totalWorkHours    = '0h 0m';
    public string $requiredHours     = '0h 0m';
    public string $overtimeHours     = '0h 0m';
    public string $pendingHours      = '0h 0m';

    public function mount(): void
    {
        // Security: this component is ONLY for users with attendance.own permission
        abort_unless(auth()->user()?->can('attendance.own'), 403);

        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->endOfMonth()->format('Y-m-d');

        $this->computeAnalytics();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
        $this->computeAnalytics();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
        $this->computeAnalytics();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    #[On('refresh-table')]
    public function refresh(): void
    {
        $this->resetPage();
        $this->computeAnalytics();
    }

    private function computeAnalytics(): void
    {
        $userId = authId();
        $from   = Carbon::parse($this->dateFrom)->startOfDay();
        $to     = Carbon::parse($this->dateTo)->endOfDay();

        // All attendance records in range
        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->get();

        // Work schedule settings
        $settings = Cache::remember('settings', 600, function () {
            return Setting::pluck('value', 'key');
        });

        // Holidays in range
        $holidays = Holiday::query()
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start', [$from, $to])
                    ->orWhereBetween('end', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('start', '<=', $from)->where('end', '>=', $to);
                    });
            })
            ->get();

        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $period = CarbonPeriod::create(
                Carbon::parse($holiday->start)->toDateString(),
                Carbon::parse($holiday->end)->toDateString()
            );
            foreach ($period as $date) {
                $holidayDates[] = $date->toDateString();
            }
        }
        $holidayDates = array_unique($holidayDates);

        // Approved leaves in range
        $approvedLeaves = LeaveRequest::query()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_datetime', [$from, $to])
                    ->orWhereBetween('end_datetime', [$from, $to]);
            })
            ->get();

        $leaveDates = [];
        foreach ($approvedLeaves as $leave) {
            $period = CarbonPeriod::create(
                Carbon::parse($leave->start_datetime)->toDateString(),
                Carbon::parse($leave->end_datetime)->toDateString()
            );
            foreach ($period as $date) {
                $leaveDates[] = $date->toDateString();
            }
        }
        $leaveDates = array_unique($leaveDates);

        // Iterate over all dates in the range to compute working days
        $period = CarbonPeriod::create($from->toDateString(), $to->toDateString());
        $workingDays     = 0;
        $requiredMinutes = 0;
        $dayNames        = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

        foreach ($period as $date) {
            if ($date->isAfter(now())) {
                break; // Don't count future days
            }
            $dayName = strtolower($date->format('l'));

            // Skip non-working days per schedule
            $isWorking = filter_var($settings["{$dayName}_working"] ?? false, FILTER_VALIDATE_BOOLEAN);
            if (! $isWorking) {
                continue;
            }

            // Skip holidays
            if (in_array($date->toDateString(), $holidayDates)) {
                continue;
            }

            $workingDays++;
            $requiredMinutes += (int) ($settings["{$dayName}_required_minutes"] ?? 480);
        }

        $presentDays  = $attendances->where('status', 'present')->count();
        $leaveDays    = count(array_intersect($leaveDates, array_map(
            fn($a) => $a->attendance_date instanceof Carbon
                ? $a->attendance_date->toDateString()
                : Carbon::parse($a->attendance_date)->toDateString(),
            $attendances->toArray()
        )));
        // Safer leave count
        $leaveDays = $attendances->where('status', 'leave')->count();
        // Also count leave requests not already in attendance
        $extraLeaveDays = count(array_diff($leaveDates, $attendances->pluck('attendance_date')->map(
            fn($d) => Carbon::parse($d)->toDateString()
        )->toArray()));
        $leaveDays += $extraLeaveDays;

        $totalWorkMinutes  = $attendances->sum('total_work_minutes');
        $totalOvertimeMinutes = $attendances->sum('overtime_minutes');

        $absentDays = max(0, $workingDays - $presentDays - $leaveDays);

        // Required minutes vs actual
        $pendingMinutes = max(0, $requiredMinutes - $totalWorkMinutes);

        $this->presentDays   = $presentDays;
        $this->absentDays    = $absentDays;
        $this->leaveDays     = $leaveDays;
        $this->totalWorkHours = $this->minutesToHoursMinutes($totalWorkMinutes);
        $this->requiredHours  = $this->minutesToHoursMinutes($requiredMinutes);
        $this->overtimeHours  = $this->minutesToHoursMinutes($totalOvertimeMinutes);
        $this->pendingHours   = $this->minutesToHoursMinutes($pendingMinutes);
    }

    private function minutesToHoursMinutes(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return "{$h}h {$m}m";
    }

    public function render()
    {
        // Security enforcement: only own data
        abort_unless(auth()->user()?->can('attendance.own'), 403);

        $userId = authId();
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
