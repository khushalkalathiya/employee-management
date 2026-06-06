<?php

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateRoleAction
{
    use AsAction;

    public function handle(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'display_name' => $data['display_name'],
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($data['permissions'] ?? []);

            return $role->fresh('permissions');
        });
    }
}
