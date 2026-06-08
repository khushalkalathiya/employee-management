<?php

namespace App\Actions\Holiday;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateHolidayAction
{
    use AsAction;

    public function handle(Holiday $holiday, array $data): Holiday
    {
        return DB::transaction(function () use ($holiday, $data) {
            $holiday->update([
                'name'       => $data['name'],
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'start_time' => $data['start_time'] ?? null,
                'end_time'   => $data['end_time'] ?? null,
                'notes'      => $data['notes'] ?? null,
            ]);

            return $holiday->fresh();
        });
    }
}
