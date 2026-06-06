<?php

namespace App\Http\Controllers;

use App\Actions\Department\CreateDepartmentAction;
use App\Actions\Department\DeleteDepartmentAction;
use App\Actions\Department\UpdateDepartmentAction;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

    public function store(StoreDepartmentRequest $request, CreateDepartmentAction $action)
    {
        try {
            $action->handle($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateDepartmentRequest $request, Department $department, UpdateDepartmentAction $action)
    {
        try {
            $action->handle($department, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Department $department, DeleteDepartmentAction $action): JsonResponse
    {
        try {
            $action->handle($department);

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Department $department)
    {
        try {
            DB::transaction(function () use ($department) {
                $department->update([
                    'is_active' => ! $department->is_active,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => $department->is_active ? 'Department activated successfully.' : 'Department deactivated successfully.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department status.',
            ], 500);
        }
    }
}
