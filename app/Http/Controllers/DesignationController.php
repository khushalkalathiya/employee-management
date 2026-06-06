<?php

namespace App\Http\Controllers;

use App\Actions\Designation\CreateDesignationAction;
use App\Actions\Designation\DeleteDesignationAction;
use App\Actions\Designation\UpdateDesignationAction;
use App\Http\Requests\Designation\StoreDesignationRequest;
use App\Http\Requests\Designation\UpdateDesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\JsonResponse;

class DesignationController extends Controller
{
    public function index()
    {
        $departments = Department::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('designations.index', compact('departments'));
    }

    public function store(StoreDesignationRequest $request, CreateDesignationAction $action)
    {
        try {
            $action->handle($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateDesignationRequest $request, Designation $designation, UpdateDesignationAction $action)
    {
        try {
            $action->handle($designation, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Designation updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Designation $designation, DeleteDesignationAction $action): JsonResponse
    {
        try {
            $action->handle($designation);

            return response()->json([
                'success' => true,
                'message' => 'Designation deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
