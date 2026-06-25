<?php

namespace App\Actions\Payroll;

use App\Models\Salary;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePayrollAction
{
    use AsAction;

    public function handle(Salary $salary, array $data): Salary
    {
        return DB::transaction(function () use ($salary, $data) {

            $pfAmount        = (float) ($data['pf_amount'] ?? $salary->pf_amount);
            $otherDeductions = (float) ($data['other_deductions'] ?? $salary->other_deductions);
            $holdAmount      = (float) ($data['hold_amount'] ?? $salary->hold_amount);

            $finalSalary = max(0, (float) $salary->earned_salary - $pfAmount - $otherDeductions - $holdAmount);

            $salary->update([
                'pf_amount'        => $pfAmount,
                'other_deductions' => $otherDeductions,
                'hold_amount'      => $holdAmount,
                'final_salary'     => $finalSalary,
                'notes'            => $data['notes'] ?? $salary->notes,
                'status'           => $data['status'] ?? $salary->status,
                'paid_at'          => ($data['status'] ?? '') === 'paid' && ! $salary->paid_at
                    ? now()
                    : $salary->paid_at,
            ]);

            return $salary->fresh();
        });
    }
}
