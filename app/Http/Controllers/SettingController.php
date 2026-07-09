<?php

namespace App\Http\Controllers;

use App\Actions\Setting\UpdateGeneralSettingAction;
use App\Actions\Setting\UpdateWorkScheduleAction;
use App\Http\Requests\Setting\GeneralSettingRequest;
use App\Http\Requests\Setting\WorkScheduleRequest;
use App\Models\Setting;


class SettingController extends Controller
{
    /**
     * Display the general settings page.
     */
    public function general()
    {
        $settings = Setting::whereIn('key', ['app_name', 'app_email', 'app_logo', 'app_favicon'])
            ->pluck('value', 'key')
            ->toArray();

        return view('setting.general', compact('settings'));
    }

    /**
     * Persist general settings.
     */
    public function updateGeneral(GeneralSettingRequest $request, UpdateGeneralSettingAction $action)
    {
        try {
            $action->handle($request->validated());

            return redirect()
                ->route('settings.general.index')
                ->with('success', 'General settings updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    public function workSchedule()
    {
        $keys = [
            'timing_mode',
            'late_allowance_minutes',
            'early_clock_in_minutes',
            'early_clock_out_minutes',
            'allow_off_day_attendance',
            'break_mode',
            'break_notification_before_seconds',
        ];

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $keys[] = "{$day}_working";
            $keys[] = "{$day}_start_time";
            $keys[] = "{$day}_end_time";
            $keys[] = "{$day}_break_enabled";
            $keys[] = "{$day}_break_start";
            $keys[] = "{$day}_break_end";
        }

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return view('setting.work-schedule', compact('settings'));
    }

    public function updateWorkSchedule(WorkScheduleRequest $request, UpdateWorkScheduleAction $action)
    {
        try {
            $action->handle($request->validated());

            return redirect()
                ->route('settings.work-schedule.index')
                ->with('success', 'Work schedule updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
