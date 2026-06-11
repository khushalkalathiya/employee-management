<?php

namespace App\Http\Controllers;

use App\Actions\Role\CreateRoleAction;
use App\Actions\Role\DeleteRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    public function create()
    {
        $permissions = $this->permissions();
        $crudPermissions = $permissions['crud'];
        $otherPermissions = $permissions['other'];

        return view('roles.create', compact('crudPermissions', 'otherPermissions'));
    }

    public function store(StoreRoleRequest $request, CreateRoleAction $action)
    {
        try {
            $action->handle($request->validated());

            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = $this->permissions();
        $crudPermissions = $permissions['crud'];
        $otherPermissions = $permissions['other'];

        return view('roles.edit', compact('role', 'crudPermissions', 'otherPermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action)
    {
        try {
            $action->handle($role, $request->validated());

            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Role $role, DeleteRoleAction $action): JsonResponse
    {
        try {
            $action->handle($role);

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    private function permissions()
    {
        $permissions = Permission::query()->get();

        $otherModules = [
            'dashboard',
            'settings',
        ];

        return [
            'crud' => $permissions
                ->filter(function ($permission) use ($otherModules) {
                    $module = str($permission->name)->before('.')->toString();

                    return ! in_array($module, $otherModules);
                })
                ->groupBy(fn ($permission) => ucfirst(
                    str($permission->name)->before('.')->toString()
                ))
                ->map(function ($permissions, $module) {

                    return [
                        'own'    => $permissions->firstWhere('name', strtolower($module) . '.own'),
                        'view'   => $permissions->firstWhere('name', strtolower($module) . '.view'),
                        'create' => $permissions->firstWhere('name', strtolower($module) . '.create'),
                        'edit'   => $permissions->firstWhere('name', strtolower($module) . '.edit'),
                        'delete' => $permissions->firstWhere('name', strtolower($module) . '.delete'),
                    ];
                }),

            'other' => $permissions
                ->filter(function ($permission) use ($otherModules) {
                    $module = str($permission->name)->before('.')->toString();
                    return in_array($module, $otherModules);
                })
                ->groupBy(fn ($permission) => ucfirst(
                    str($permission->name)->before('.')->toString()
                ))
                ->map(function ($permissions) {

                    return $permissions
                        ->groupBy(function ($permission) {

                            $parts = explode('.', $permission->name);

                            return str($parts[1])
                                ->replace('_', ' ')
                                ->title();
                        })
                        ->map(function ($groupPermissions) {

                            return [
                                'own'    => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.own')),
                                'view'   => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.view')),
                                'create' => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.create')),
                                'edit'   => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.edit')),
                                'update' => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.update')),
                                'delete' => $groupPermissions->first(fn ($p) => str($p->name)->endsWith('.delete')),
                            ];
                        });

                }),
        ];
    }
}
