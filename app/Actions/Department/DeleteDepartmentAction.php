<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteDepartmentAction
{
    use AsAction;

    public function handle(Department $department): bool
    {
        return DB::transaction(fn () => $department->delete());
    }
}
