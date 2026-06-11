<?php

namespace App\Http\Controllers;

use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Http\Requests\Leave\UpdateLeaveRequest;
use App\Models\LeaveRequest;
use App\Actions\Leave\CreateLeaveRequestAction;
use App\Actions\Leave\UpdateLeaveRequestAction;
use App\Actions\Leave\DeleteLeaveRequestAction;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the leave requests.
     */
    public function index()
    {
        $users = User::where('is_active', true)
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'superadmin');
            })
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->mapWithKeys(fn ($user) => [
                $user->id => $user->first_name . ' ' . $user->last_name
            ])
            ->toArray();

        $leaveTypes = LeaveType::all()->pluck('name', 'id');

        return view('leave.index', compact('users', 'leaveTypes'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(StoreLeaveRequest $request, CreateLeaveRequestAction $action): JsonResponse
    {
        try {
            $leave = $action->handle($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Leave request created successfully.',
                'data'    => $leave,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function show(LeaveRequest $leave)
    {
        $leave->load([
            'user',
            'leaveType',
            'approver',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $leave->user_id ?? '',
                'employee_name' => $leave->user?->full_name ?? '',
                'employee_email' => $leave->user?->email ?? '',
                'employee_avatar' => $leave->user?->avatar ?? '',
                'employee_initials' => optional($leave->user)->initials ?? 'NA',
                'leave_type' => $leave->leaveType?->name ?? '',
                'leave_mode' => $leave->leave_mode ?? '',
                'start_datetime' => $leave->start_datetime ?? '',
                'end_datetime' => $leave->end_datetime ?? '',
                'reason' => $leave->reason ?? '',
                'status' => $leave->status ?? 0,
                'approved_by' => $leave->approver?->full_name ?? '',
                'approved_at' => $leave->approved_at ?? '',
                'rejection_reason' => $leave->rejection_reason ?? '',
                'created_at' => $leave->created_at ?? '',
            ]
        ]);
    }

    /**
     * Update an existing leave request.
     */
    public function update(UpdateLeaveRequest $request, LeaveRequest $leave, UpdateLeaveRequestAction $action): JsonResponse
    {
        try {
            $updated = $action->handle($leave, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Leave request updated successfully.',
                'data'    => $updated,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(LeaveRequest $leave, DeleteLeaveRequestAction $action): JsonResponse
    {
        try {
            $action->handle($leave);
            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        try {
            $request->validate([
                'status' => ['required', 'in:1,2,3'],
                'rejection_reason' => ['required_if:status,2', 'nullable', 'string'],
            ]);
            if (has_permission('leave.own') && $leave->user_id !== authId()) {
                abort(403, 'Unauthorized');
            }
            DB::transaction(function () use ($leave, $request) {
                if($request->status == 1){
                    $leave->approved_by = authId();
                    $leave->approved_at = now();
                } elseif($request->status == 2){
                    $leave->rejection_reason = $request->rejection_reason ?? null;
                }
                $leave->status = $request->status ?? 0;
                $leave->save();
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Leave status updated successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
