<?php

namespace App\Actions\Designation;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDesignationAction
{
    use AsAction;

    public function handle(array $data): Designation
    {
        return DB::transaction(function () use ($data) {
            return Designation::create([
                'department_id' => $data['department_id'],
                'name' => $data['name'],
            ]);
        });
    }
}
