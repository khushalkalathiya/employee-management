<?php

namespace App\Actions\Designation;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteDesignationAction
{
    use AsAction;

    public function handle(Designation $designation): bool
    {
        return DB::transaction(fn () => $designation->delete());
    }
}
