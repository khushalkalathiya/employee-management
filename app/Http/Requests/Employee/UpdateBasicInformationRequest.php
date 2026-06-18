<?php

namespace App\Http\Requests\Employee;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        if (authId() === $user->id) {
            return true;
        }
        return has_permission('employee.edit');
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $rules = [
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email:rfc,dns',Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['required','string','max:20'],
        ];

        if(authId() != $user->id){
            $rules['role'] = ['required','string'];
            $rules['joining_date'] = ['required', 'date'];
        }

        return $rules;
    }
}