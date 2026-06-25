<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('payroll.create');
    }

    public function rules(): array
    {
        return [
            'employee_id'      => ['required', 'integer', 'exists:employees,id'],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after_or_equal:start_date'],
            'pf_amount'        => ['nullable', 'numeric', 'min:0'],
            'other_deductions' => ['nullable', 'numeric', 'min:0'],
            'hold_amount'      => ['nullable', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
        ];
    }
}
