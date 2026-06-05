<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('user.edit');
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email:rfc,dns',Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['required','string','max:20'],
            'role' => ['required','string'],
            'joining_date' => ['required', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
        ];
    }
}