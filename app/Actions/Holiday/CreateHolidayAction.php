<?php

namespace App\Actions\Holiday;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateHolidayAction
{
    use AsAction;

    public function handle(array $data): Holiday
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['is_multiple_days'])) {
                $start = $data['start_date'];
                $end = $data['end_date'];
            } elseif (!empty($data['is_partial_day'])) {
                $start = $data['holiday_date'] . ' ' . $data['start_time'];
                $end = $data['holiday_date'] . ' ' . $data['end_time'];
            } else {
                $start = $data['holiday_date'] . ' 00:00:00';
                $end = $data['holiday_date'] . ' 23:59:59';
            }

            return Holiday::create([
                'name' => $data['name'],
                'start' => $start,
                'end' => $end,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }
}