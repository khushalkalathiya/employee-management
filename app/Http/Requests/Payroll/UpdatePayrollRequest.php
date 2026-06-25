<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('payroll.edit');
    }

    public function rules(): array
    {
        return [
            'pf_amount'        => ['nullable', 'numeric', 'min:0'],
            'other_deductions' => ['nullable', 'numeric', 'min:0'],
            'hold_amount'      => ['nullable', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:2000'],
            'status'           => ['nullable', 'in:pending,paid,cancelled'],
        ];
    }
}
