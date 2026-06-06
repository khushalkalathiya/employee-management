<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Actions\Employee\CreateEmployeeAction;
use App\Actions\Employee\UpdateEmployeeAction;
use App\Actions\Employee\DeleteEmployeeAction;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        $roles = Role::pluck('display_name','name');
        return view('employees.create', compact('roles'));
    }

    public function store(StoreEmployeeRequest $request, CreateEmployeeAction $action ) {
        try {
            $action->handle($request->validated());
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        $roles = Role::pluck('display_name','name');

        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(UpdateEmployeeRequest $request, User $employee, UpdateEmployeeAction $action) {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $employee, DeleteEmployeeAction $action): JsonResponse {
        try {
            $action->handle($employee);
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}