<?php

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteRoleAction
{
    use AsAction;

    public function handle(Role $role): bool
    {
        return DB::transaction(function () use ($role) {
            $role->syncPermissions([]);

            return $role->delete();
        });
    }
}
