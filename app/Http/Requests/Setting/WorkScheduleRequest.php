<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class WorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('settings.work_schedule.edit');
    }

    public function rules(): array
    {
        $timingMode = $this->input('timing_mode');
        $breakMode = $this->input('break_mode');

        $rules = [
            'timing_mode'                        => ['required', 'in:fixed,flexible'],
            'break_mode'                         => ['required', 'in:fixed,flexible'],
            'late_allowance_minutes'             => ['required', 'integer', 'min:0'],
            'early_clock_in_minutes'             => [$timingMode === 'fixed' ? 'required' : 'nullable', 'integer', 'min:0'],
            'early_clock_out_minutes'            => [$timingMode === 'fixed' ? 'required' : 'nullable', 'integer', 'min:0'],
            'allow_off_day_attendance'           => ['nullable', 'boolean'],
            'break_notification_before_seconds'  => [$breakMode === 'fixed' ? 'required' : 'nullable', 'integer', 'min:0'],
        ];

        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        foreach ($days as $day) {
            $rules["{$day}_working"] = ['nullable', 'boolean'];
            if ($timingMode === 'fixed') {
                $rules["{$day}_start_time"] = ["required_if:{$day}_working,1", 'nullable', 'date_format:g:i A'];
                $rules["{$day}_end_time"] = ["required_if:{$day}_working,1", 'nullable', 'date_format:g:i A'];
            } else {
                $rules["{$day}_start_time"] = ['nullable', 'date_format:g:i A'];
                $rules["{$day}_end_time"] = ['nullable', 'date_format:g:i A'];
            }
            $rules["{$day}_break_enabled"] = ['nullable', 'boolean'];
            if ($breakMode === 'fixed') {
                $rules["{$day}_break_start"] = ["required_if:{$day}_break_enabled,1", 'nullable', 'date_format:g:i A'];
                $rules["{$day}_break_end"] = ["required_if:{$day}_break_enabled,1", 'nullable', 'date_format:g:i A'];
            } else {
                $rules["{$day}_break_start"] = ['nullable', 'date_format:g:i A'];
                $rules["{$day}_break_end"] = ['nullable', 'date_format:g:i A'];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        foreach ([
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ] as $day) {
            $label = ucfirst($day);
            $messages["{$day}_start_time.required_if"] = "Start time is required when working day is enabled.";
            $messages["{$day}_end_time.required_if"] = "End time is required when working day is enabled.";
            $messages["{$day}_break_start.required_if"] = "Break start time is required when break is enabled.";
            $messages["{$day}_break_end.required_if"] = "Break end time is required when break is enabled.";
        }

        return $messages;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            
            $days = [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ];

            foreach ($days as $day) {

                if (!$this->boolean("{$day}_working")) {
                    continue;
                }

                $start = $this->input("{$day}_start_time");
                $end = $this->input("{$day}_end_time");

                if ($start && $end) {
                    if (strtotime($end) <= strtotime($start)) {
                        $validator->errors()->add(
                            "{$day}_end_time", 
                            ucfirst($day) . ' end time must be after start time.'
                        );
                    }
                }

                if (!$this->boolean("{$day}_break_enabled")) {
                    continue;
                }

                $breakStart = $this->input("{$day}_break_start");
                $breakEnd = $this->input("{$day}_break_end");

                if ($breakStart && $breakEnd) {

                    if (strtotime($breakEnd) <= strtotime($breakStart)) {
                        $validator->errors()->add(
                            "{$day}_break_end",
                            ucfirst($day) . ' break end time must be after break start time.'
                        );
                    }

                    if ($start && $end) {
                        if (strtotime($breakStart) < strtotime($start) || strtotime($breakEnd) > strtotime($end)) {
                            $validator->errors()->add(
                                "{$day}_break_start",
                                ucfirst($day) . ' break time must be inside working hours.'
                            );
                        }
                    }
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'timing_mode'                       => 'timing mode',
            'break_mode'                        => 'break mode',
            'late_allowance_minutes'            => 'late allowance minutes',
            'early_clock_in_minutes'            => 'early clock in minutes',
            'early_clock_out_minutes'           => 'early clock out minutes',
            'allow_off_day_attendance'          => 'allow off-day attendance',
            'break_notification_before_seconds' => 'break notification before seconds',
        ];
    }

}
