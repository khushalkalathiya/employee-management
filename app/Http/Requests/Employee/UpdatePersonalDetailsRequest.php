<?php

namespace App\Http\Requests\Employee;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        if (authId() === $user->id) {
            return true;
        }
        return has_permission('employee.edit');
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $employee = $user ? $user->employee : null;
        $employeeId = $employee ? $employee->id : null;

        $rules = [
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'marital_status' => ['nullable', 'string', 'in:single,married,divorced,widowed'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'auto_break_enabled' => ['nullable', 'boolean'],
        ];

        if(authId() != $user->id){
            $rules['employee_code'] = ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($employeeId)];
            $rules['department_id'] = ['nullable', 'exists:departments,id'];
            $rules['designation_id'] = ['nullable', 'exists:designations,id'];
            $rules['employment_type'] = ['nullable', 'string', 'in:permanent,contract,intern,freelancer'];
            $rules['reporting_manager_id'] = ['nullable', 'exists:users,id'];
            $rules['status'] = ['required', 'in:active,probation,notice,resigned,terminated'];
            $rules['current_salary'] = ['nullable', 'numeric', 'min:0'];
            $rules['joining_date'] = ['nullable', 'date'];
            $rules['probation_end_date'] = ['nullable', 'date'];
        }

        return $rules;
    }
}
