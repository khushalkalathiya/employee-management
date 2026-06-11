<?php

namespace App\Http\Controllers;

use App\Actions\Setting\UpdateWorkScheduleAction;
use App\Http\Requests\Setting\WorkScheduleRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the general settings page.
     */
    public function general()
    {
        return view('setting.general');
    }
    
    public function workSchedule()
    {
        $keys = [
            'late_allowance_minutes'
        ];
        foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $keys[] = "{$day}_working";
            $keys[] = "{$day}_start_time";
            $keys[] = "{$day}_end_time";
            $keys[] = "{$day}_break_enabled";
            $keys[] = "{$day}_break_start";
            $keys[] = "{$day}_break_end";
        }

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return view('setting.work-schedule', compact(
            'settings'
        ));
    }
    
    public function updateWorkSchedule(WorkScheduleRequest $request, UpdateWorkScheduleAction $action)
    {
        try {
            $action->handle($request->validated());
            return redirect()->route('settings.work-schedule.index')->with('success', 'Work schedule updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
