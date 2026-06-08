<?php

namespace App\Actions\Holiday;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteHolidayAction
{
    use AsAction;

    public function handle(Holiday $holiday): bool
    {
        return DB::transaction(fn () => $holiday->delete());
    }
}
