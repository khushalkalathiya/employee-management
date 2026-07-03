<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('settings.general.edit');
    }

    public function rules(): array
    {
        return [
            'app_name'    => ['required', 'string', 'max:100'],
            'app_email'   => ['required', 'email', 'max:150'],
            'app_logo'    => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'app_favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,ico,svg,webp', 'max:512'],
        ];
    }

    public function attributes(): array
    {
        return [
            'app_name'    => 'app name',
            'app_email'   => 'app email',
            'app_logo'    => 'app logo',
            'app_favicon' => 'app favicon',
        ];
    }
}
