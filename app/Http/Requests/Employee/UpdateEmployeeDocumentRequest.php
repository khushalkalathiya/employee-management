<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeDocumentRequest extends FormRequest
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
        return [
            'document_type' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:5120'], // Max 5MB, optional on update
        ];
    }
}
