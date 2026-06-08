<?php

namespace App\Http\Requests\Holiday;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return has_permission('holiday.edit');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_multiple_days' => $this->boolean('is_multiple_days'),
            'is_partial_day' => $this->boolean('is_partial_day'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_multiple_days' => ['nullable', 'boolean'],
            'is_partial_day' => ['nullable', 'boolean'],
            'holiday_date' => ['required_if:is_multiple_days,false', 'nullable', 'date'],
            'start_date' => ['required_if:is_multiple_days,1', 'nullable', 'date'],
            'end_date' => ['required_if:is_multiple_days,1', 'nullable', 'date'],
            'start_time' => ['required_if:is_partial_day,1', 'nullable', 'date_format:H:i'],
            'end_time' => ['required_if:is_partial_day,1', 'nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $isMultiple = $this->boolean('is_multiple_days');
            $isPartial = $this->boolean('is_partial_day');

            if ($isMultiple) {

                if (!$this->start_date || !$this->end_date) {
                    return;
                }

                $startDate = Carbon::parse($this->start_date);
                $endDate = Carbon::parse($this->end_date);

                if ($startDate->gt($endDate)) {
                    $validator->errors()->add(
                        'end_date',
                        'End date must be after start date.'
                    );
                }

                if ($startDate->equalTo($endDate)) {
                    $validator->errors()->add(
                        'end_date',
                        'Multiple days holiday must be at least 1 day long.'
                    );
                }
            }

            if (!$isMultiple && $isPartial) {

                if (!$this->start_time || !$this->end_time) {
                    return;
                }

                $startTime = Carbon::createFromFormat(
                    'H:i',
                    $this->start_time
                );

                $endTime = Carbon::createFromFormat(
                    'H:i',
                    $this->end_time
                );

                if ($startTime->gte($endTime)) {
                    $validator->errors()->add(
                        'end_time',
                        'End time must be after start time.'
                    );
                }

                if ($startTime->diffInMinutes($endTime) < 10) {
                    $validator->errors()->add(
                        'end_time',
                        'Minimum holiday duration is 10 minutes.'
                    );
                }
            }
        });
    }
}