<?php

namespace App\Http\Controllers;

use App\Actions\LeaveType\CreateLeaveTypeAction;
use App\Actions\LeaveType\DeleteLeaveTypeAction;
use App\Actions\LeaveType\UpdateLeaveTypeAction;
use App\Http\Requests\LeaveType\StoreLeaveTypeRequest;
use App\Http\Requests\LeaveType\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Http\JsonResponse;

class LeaveTypeController extends Controller
{
    public function index()
    {
        return view('leave-types.index');
    }

    public function store(StoreLeaveTypeRequest $request, CreateLeaveTypeAction $action)
    {
        try {
            $action->handle($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Leave Type created successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType, UpdateLeaveTypeAction $action)
    {
        try {
            $action->handle($leaveType, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Leave Type updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(LeaveType $leaveType, DeleteLeaveTypeAction $action): JsonResponse
    {
        try {
            $action->handle($leaveType);

            return response()->json([
                'success' => true,
                'message' => 'Leave Type deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
