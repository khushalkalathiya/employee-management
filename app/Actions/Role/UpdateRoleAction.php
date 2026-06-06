<?php

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateRoleAction
{
    use AsAction;

    public function handle(Role $role, array $data): Role
    {    
        if ($role->name === 'superadmin') {
            if (! has_role('superadmin')) {
                abort(403, 'Super Admin role cannot be modified.');
            }
            $data['name'] = 'superadmin';
            $data['permissions'] = collect($data['permissions'] ?? [])
                ->merge([
                    'role.view',
                    'role.create',
                    'role.edit',
                    'role.delete',
                ])
                ->unique()
                ->values()
                ->toArray();
        }
        
        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'display_name' => $data['display_name'],
                'name' => $data['name'],
            ]);

            $role->syncPermissions($data['permissions'] ?? []);

            return $role->fresh('permissions');
        });
    }
}
