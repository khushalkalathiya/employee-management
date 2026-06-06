<?php

namespace App\Http\Requests\Designation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('designation.edit');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $designation = $this->route('designation');

        return [
            '_form' => ['nullable', 'string'],
            '_edit_id' => ['nullable', 'integer'],
            'department_id' => ['required', 'integer', Rule::exists('departments', 'id')->whereNull('deleted_at')],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations', 'name')
                    ->where('department_id', $this->input('department_id'))
                    ->ignore($designation),
            ],
        ];
    }
}
