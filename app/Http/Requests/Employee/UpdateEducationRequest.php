<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('employee.edit');
    }

    public function rules(): array
    {
        return [
            'qualification' => ['required', 'string', 'max:255'],
            'institute_name' => ['required', 'string', 'max:255'],
            'board_university' => ['nullable', 'string', 'max:255'],
            'passing_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'percentage_grade' => ['nullable', 'string', 'max:20'],
        ];
    }
}
