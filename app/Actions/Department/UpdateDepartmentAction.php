<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDepartmentAction
{
    use AsAction;

    public function handle(Department $department, array $data): Department
    {
        return DB::transaction(function () use ($department, $data) {
            $department->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $department->fresh();
        });
    }
}
