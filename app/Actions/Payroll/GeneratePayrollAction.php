<?php

namespace App\Actions\Payroll;

use Carbon\Carbon;
use App\Models\Salary;
use App\Models\Employee;
use App\Services\PayrollCalculationService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GeneratePayrollAction
{
    use AsAction;

    public function __construct(protected PayrollCalculationService $calculator)
    {
    }

    public function handle(array $data): Salary
    {
        return DB::transaction(function () use ($data) {

            $employee  = Employee::findOrFail($data['employee_id']);
            $startDate = Carbon::parse($data['start_date'])->startOfDay();
            $endDate   = Carbon::parse($data['end_date'])->endOfDay();

            $calc = $this->calculator->calculate($employee, $startDate, $endDate);

            // Merge user-supplied overrides (deductions, notes, etc.)
            $pfAmount        = (float) ($data['pf_amount'] ?? 0);
            $otherDeductions = (float) ($data['other_deductions'] ?? 0);
            $holdAmount      = (float) ($data['hold_amount'] ?? 0);

            $earnedSalary = $calc['earned_salary'];
            $finalSalary  = max(0, $earnedSalary - $pfAmount - $otherDeductions - $holdAmount);

            return Salary::create([
                'employee_id'      => $employee->id,
                'salary_month'     => $startDate->copy()->startOfMonth(),
                'start_date'       => $startDate,
                'end_date'         => $endDate,
                'is_hourly'        => $calc['is_hourly'],
                'hourly_rate'      => $calc['hourly_rate'],
                'total_hours'      => $calc['total_hours'],
                'working_days'     => $calc['working_days'],
                'present_days'     => $calc['present_days'],
                'leave_days'       => $calc['leave_days'],
                'per_day_salary'   => $calc['per_day_salary'],
                'earned_salary'    => $earnedSalary,
                'pf_amount'        => $pfAmount,
                'other_deductions' => $otherDeductions,
                'hold_amount'      => $holdAmount,
                'final_salary'     => $finalSalary,
                'notes'            => $data['notes'] ?? null,
                'status'           => 'pending',
                'processed_by'     => authId(),
            ]);
        });
    }
}
