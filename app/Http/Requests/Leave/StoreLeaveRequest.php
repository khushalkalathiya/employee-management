<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('leave.create');
    }

    public function rules()
    {
        $rules = [
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'leave_mode' => ['required', Rule::in(['full_day', 'multiple_days', 'half_day'])],
            'reason' => ['required', 'string', 'max:1000'],
        ];
        if(!has_permission('leave.own')) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        switch ($this->input('leave_mode')) {
            case 'full_day':
                $rules['leave_date'] = ['required', 'date'];
                break;
            case 'multiple_days':
                $rules['start_datetime'] = ['required', 'date'];
                $rules['end_datetime'] = ['required', 'date', 'after:start_datetime'];
                break;
            case 'half_day':
                $rules['leave_date'] = ['required', 'date'];
                $rules['start_time'] = ['required', 'date_format:g:i A'];
                $rules['end_time'] = ['required', 'date_format:g:i A'];
                break;
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if ($this->leave_mode === 'multiple_days') {
                
                $start = \Carbon\Carbon::parse($this->start_datetime);
                $end = \Carbon\Carbon::parse($this->end_datetime);

                if ($start->diffInDays($end) < 1) {
                    $validator->errors()->add(
                        'end_datetime',
                        'Multiple day leave must be at least 1 day long.'
                    );
                }
            }

            if ($this->leave_mode === 'half_day') {

                if (!$this->start_time || !$this->end_time) {
                    return;
                }

                $startTime = \Carbon\Carbon::createFromFormat(
                    'g:i A',
                    $this->start_time
                );

                $endTime = \Carbon\Carbon::createFromFormat(
                    'g:i A',
                    $this->end_time
                );

                if ($startTime->gte($endTime)) {
                    $validator->errors()->add(
                        'end_time',
                        'End time must be after start time.'
                    );
                }

                if ($startTime->diffInMinutes($endTime) < 30) {
                    $validator->errors()->add(
                        'end_time',
                        'Leave duration must be at least 30 minutes.'
                    );
                }
            }
        });
    }
}
?>
