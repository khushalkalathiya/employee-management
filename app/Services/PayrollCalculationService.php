<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Support\Collection;

class PayrollCalculationService
{
    /**
     * The configured working weekdays (lowercase names).
     */
    protected array $workingDays = [];

    public function __construct()
    {
        $this->workingDays = $this->resolveWorkingDays();
    }

    /**
     * Calculate payroll preview for a given employee and date range.
     *
     * Returns an array suitable for displaying in the "Calculate" preview
     * and for persisting to the salaries table.
     */
    public function calculate(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        if ($employee->is_hourly) {
            return $this->calculateHourly($employee, $startDate, $endDate);
        }

        return $this->calculateMonthly($employee, $startDate, $endDate);
    }

    // ─────────────────────────────────────────────────────────────
    //  Hourly calculation
    // ─────────────────────────────────────────────────────────────

    protected function calculateHourly(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        $userId      = $employee->user_id;
        $hourlyRate  = (float) $employee->current_salary;

        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotNull('check_out')
            ->get();

        $totalMinutes = $attendances->sum('total_work_minutes');
        $totalHours   = round($totalMinutes / 60, 2);
        $grossSalary  = round($totalHours * $hourlyRate, 4);

        return [
            'is_hourly'        => true,
            'hourly_rate'      => $hourlyRate,
            'total_hours'      => $totalHours,
            'working_days'     => 0,
            'present_days'     => 0,
            'leave_days'       => 0,
            'per_day_salary'   => 0,
            'earned_salary'    => $grossSalary,
            'pf_amount'        => 0,
            'other_deductions' => 0,
            'hold_amount'      => 0,
            'final_salary'     => $grossSalary,
            // Extra info for the preview card
            '_breakdown' => [
                'attendance_count' => $attendances->count(),
                'total_minutes'    => $totalMinutes,
                'gross'            => $grossSalary,
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    //  Monthly calculation
    // ─────────────────────────────────────────────────────────────

    protected function calculateMonthly(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        $userId      = $employee->user_id;
        $baseSalary  = (float) $employee->current_salary;

        // Count scheduled working days in the period (according to Settings)
        $scheduledDays = $this->countScheduledWorkingDays($startDate, $endDate);

        if ($scheduledDays === 0) {
            $perDaySalary = 0;
        } else {
            $perDaySalary = round($baseSalary / $scheduledDays, 4);
        }

        // Fetch attendances for the period
        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // Count by status  (Present, Half-Day, Leave, Absent)
        $presentCount  = 0;
        $halfDayCount  = 0;
        $leaveCount    = 0;

        foreach ($attendances as $a) {
            $status = strtolower($a->status ?? '');

            if ($status === 'present') {
                $presentCount++;
            } elseif (in_array($status, ['half_day', 'half-day', 'halfday'])) {
                $halfDayCount++;
            } elseif ($status === 'leave') {
                $leaveCount++;
            }
        }

        // Days value: present = 1.0, half-day = 0.5
        $paidDays     = $presentCount + ($halfDayCount * 0.5);
        $halfDayDeduction = $halfDayCount * 0.5 * $perDaySalary;

        // Absent days (scheduled days not covered by attendance records)
        $coveredDays  = $presentCount + $halfDayCount + $leaveCount;
        $absentDays   = max(0, $scheduledDays - $coveredDays);
        $absentDeduction = $absentDays * $perDaySalary;

        $earnedSalary = round($paidDays * $perDaySalary, 4);

        return [
            'is_hourly'        => false,
            'hourly_rate'      => 0,
            'total_hours'      => 0,
            'working_days'     => $scheduledDays,
            'present_days'     => $paidDays,
            'leave_days'       => $leaveCount,
            'per_day_salary'   => $perDaySalary,
            'earned_salary'    => $earnedSalary,
            'pf_amount'        => 0,
            'other_deductions' => 0,
            'hold_amount'      => 0,
            'final_salary'     => $earnedSalary,
            // Extra info for preview
            '_breakdown' => [
                'base_salary'        => $baseSalary,
                'scheduled_days'     => $scheduledDays,
                'present_days'       => $presentCount,
                'half_day_days'      => $halfDayCount,
                'leave_days'         => $leaveCount,
                'absent_days'        => $absentDays,
                'paid_days'          => $paidDays,
                'per_day_salary'     => $perDaySalary,
                'half_day_deduction' => $halfDayDeduction,
                'absent_deduction'   => $absentDeduction,
                'gross'              => $earnedSalary,
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * Count how many days in [startDate, endDate] fall on a configured working weekday.
     */
    public function countScheduledWorkingDays(Carbon $startDate, Carbon $endDate): int
    {
        if (empty($this->workingDays)) {
            // Fallback: Mon–Fri
            $this->workingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        }

        $count = 0;
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $day) {
            if (in_array(strtolower($day->format('l')), $this->workingDays)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Read which weekdays are enabled from the settings table.
     */
    protected function resolveWorkingDays(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $keys = array_map(fn ($d) => "{$d}_working", $days);

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $enabled = [];
        foreach ($days as $day) {
            if (filter_var($settings["{$day}_working"] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                $enabled[] = $day;
            }
        }

        return $enabled;
    }
}
