<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDepartmentAction
{
    use AsAction;

    public function handle(array $data): Department
    {
        return DB::transaction(function () use ($data) {
            return Department::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        });
    }
}
