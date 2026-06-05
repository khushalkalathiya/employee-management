<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return has_permission('user.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp','max:2048'], // 2 MB
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'phone' => ['required','string'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
            'joining_date' => ['required', 'date'],
            'gender' => ['nullable', 'in:male,female,other']
        ];
    }
}
