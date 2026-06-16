<?php

namespace App\Http\Requests\Employee;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('employee.edit');
    }

    public function rules(): array
    {
        $employeeUser = $this->route('employee');
        $employee = $employeeUser ? $employeeUser->employee : null;
        $employeeId = $employee ? $employee->id : null;

        return [
            'employee_code' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($employeeId)],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'marital_status' => ['nullable', 'string', 'in:single,married,divorced,widowed'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'joining_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', 'string', 'in:permanent,contract,intern,freelancer'],
            'reporting_manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,probation,notice,resigned,terminated'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }
}
