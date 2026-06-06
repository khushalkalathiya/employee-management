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

        return view('roles.create', compact('permissions'));
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

        return view('roles.edit', compact('role', 'permissions'));
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
        return Permission::query()
            ->get()
            ->groupBy(fn (Permission $permission) => ucfirst(
                str($permission->name)->before('.')->toString()
            ))
            ->map(function ($permissions, $module) {

                return [
                    'view' => $permissions->firstWhere('name', strtolower($module) . '.view'),
                    'create' => $permissions->firstWhere('name', strtolower($module) . '.create'),
                    'edit' => $permissions->firstWhere('name', strtolower($module) . '.edit'),
                    'delete' => $permissions->firstWhere('name', strtolower($module) . '.delete'),
                ];

            });
    }
}
