<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('employee.edit');
    }

    public function rules(): array
    {
        return [
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_phone' => ['nullable', 'string', 'max:20'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_phone' => ['nullable', 'string', 'max:20'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'spouse_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relation' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
        ];
    }
}
