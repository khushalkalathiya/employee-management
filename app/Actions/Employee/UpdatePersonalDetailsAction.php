<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePersonalDetailsAction
{
    use AsAction;

    public function handle(User $user, array $data): Employee
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $fillData = [
                'gender' => $data['gender'] ?? $employee->gender,
                'date_of_birth' => $data['date_of_birth'] ?? $employee->date_of_birth,
                'marital_status' => $data['marital_status'] ?? $employee->marital_status,
                'alternate_phone' => $data['alternate_phone'] ?? $employee->alternate_phone,
                'address' => $data['address'] ?? $employee->address,
                'city' => $data['city'] ?? $employee->city,
                'state' => $data['state'] ?? $employee->state,
                'country' => $data['country'] ?? $employee->country,
                'postal_code' => $data['postal_code'] ?? $employee->postal_code,
                'auto_break_enabled' => filter_var($data['auto_break_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];

            if(authId() != $user->id){
                $fillData['employee_code'] = $data['employee_code'] ?? $employee->employee_code;
                $fillData['department_id'] = $data['department_id'] ?? $employee->department_id;
                $fillData['designation_id'] = $data['designation_id'] ?? $employee->designation_id;
                $fillData['employment_type'] = $data['employment_type'] ?? $employee->employment_type;
                $fillData['reporting_manager_id'] = $data['reporting_manager_id'] ?? $employee->reporting_manager_id;
                $fillData['status'] = $data['status'] ?? $employee->status;
                $fillData['current_salary'] = $data['current_salary'] ?? $employee->current_salary;
                $fillData['joining_date'] = $data['joining_date'] ?? $employee->joining_date;
                $fillData['probation_end_date'] = $data['probation_end_date'] ?? $employee->probation_end_date;
            }

            $employee->update($fillData);

            return $employee;
        });
    }
}
