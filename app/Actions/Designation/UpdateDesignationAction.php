<?php

namespace App\Actions\Designation;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDesignationAction
{
    use AsAction;

    public function handle(Designation $designation, array $data): Designation
    {
        return DB::transaction(function () use ($designation, $data) {
            $designation->update([
                'department_id' => $data['department_id'],
                'name' => $data['name'],
            ]);

            return $designation->fresh('department');
        });
    }
}
