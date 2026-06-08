<?php

namespace App\Http\Controllers;

use App\Actions\Holiday\CreateHolidayAction;
use App\Actions\Holiday\DeleteHolidayAction;
use App\Actions\Holiday\UpdateHolidayAction;
use App\Http\Requests\Holiday\StoreHolidayRequest;
use App\Http\Requests\Holiday\UpdateHolidayRequest;
use App\Models\Holiday;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    public function index()
    {
        return view('holidays.index');
    }

    public function store(StoreHolidayRequest $request, CreateHolidayAction $action)
    {
        try {
            $action->handle($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Holiday created successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateHolidayRequest $request, Holiday $holiday, UpdateHolidayAction $action)
    {
        try {
            $action->handle($holiday, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Holiday $holiday, DeleteHolidayAction $action): JsonResponse
    {
        try {
            $action->handle($holiday);

            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
