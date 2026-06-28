<?php

namespace App\Http\Controllers;

use App\Models\EmployeeWork;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WorkLogController extends Controller
{
    public function index()
    {
        $employees = [];
        $user = auth()->user();
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        if (!$hasOwn) {
            $employees = Employee::with('user')
                ->where('status', 'active')
                ->get()
                ->sortBy(function ($emp) {
                    return $emp->user->full_name;
                });
        }

        return view('work-logs.index', compact('employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        $rules = [
            'date' => 'required|date|before_or_equal:today',
            'project_title' => 'required|string|max:255',
            'description' => 'required|string',
            'work_images' => 'nullable|array',
            'work_images.*' => 'image|max:5120',
        ];

        if (!$hasOwn) {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $validated = $request->validate($rules);

        try {
            $employeeId = $hasOwn ? $user->employee?->id : $validated['employee_id'];
            if (!$employeeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found.',
                ], 400);
            }

            DB::transaction(function () use ($employeeId, $validated) {
                // Find active attendance matching this date if any for target employee
                $targetEmployee = Employee::findOrFail($employeeId);
                $attendance = Attendance::where('user_id', $targetEmployee->user_id)
                    ->whereDate('attendance_date', $validated['date'])
                    ->first();

                $work = EmployeeWork::create([
                    'employee_id' => $employeeId,
                    'attendance_id' => $attendance?->id,
                    'date' => $validated['date'],
                    'project_title' => $validated['project_title'],
                    'description' => $validated['description'],
                ]);

                // Upload new images
                if (request()->hasFile('work_images')) {
                    foreach (request()->file('work_images') as $image) {
                        $work->addMedia($image->getRealPath())->toMediaCollection('work_images');
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Work log created successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, EmployeeWork $workLog): JsonResponse
    {
        $user = auth()->user();
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        if ($hasOwn) {
            abort_unless($workLog->employee_id === $user->employee?->id, 403);
        }

        $rules = [
            'date' => 'required|date|before_or_equal:today',
            'project_title' => 'required|string|max:255',
            'description' => 'required|string',
            'work_images' => 'nullable|array',
            'work_images.*' => 'image|max:5120',
        ];

        if (!$hasOwn) {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($workLog, $validated, $hasOwn) {
                $updateData = [
                    'date' => $validated['date'],
                    'project_title' => $validated['project_title'],
                    'description' => $validated['description'],
                ];

                if (!$hasOwn) {
                    $updateData['employee_id'] = $validated['employee_id'];
                    $targetEmployee = Employee::findOrFail($validated['employee_id']);
                    $attendance = Attendance::where('user_id', $targetEmployee->user_id)
                        ->whereDate('attendance_date', $validated['date'])
                        ->first();
                    $updateData['attendance_id'] = $attendance?->id;
                }

                $workLog->update($updateData);

                // Upload new images
                if (request()->hasFile('work_images')) {
                    foreach (request()->file('work_images') as $image) {
                        $workLog->addMedia($image->getRealPath())->toMediaCollection('work_images');
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Work log updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(EmployeeWork $workLog): JsonResponse
    {
        $user = auth()->user();
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        if ($hasOwn) {
            abort_unless($workLog->employee_id === $user->employee?->id, 403);
        }

        try {
            $workLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Work log deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeMedia(Media $media): JsonResponse
    {
        $work = EmployeeWork::findOrFail($media->model_id);
        
        $user = auth()->user();
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        if ($hasOwn) {
            abort_unless($work->employee_id === $user->employee?->id, 403);
        }

        try {
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
