<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('employee.edit');
    }

    public function rules(): array
    {
        return [
            'asset_type' => ['required', 'string', 'max:255'],
            'asset_name' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
