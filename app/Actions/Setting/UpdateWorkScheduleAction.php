<?php

namespace App\Actions\Setting;

use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkScheduleAction
{
    use AsAction;

    public function handle(array $data): void
    {
        DB::transaction(function () use ($data) {

            Setting::updateOrCreate(
                ['key' => 'timing_mode'],
                ['value' => $data['timing_mode']]
            );

            Setting::updateOrCreate(
                ['key' => 'break_mode'],
                ['value' => $data['break_mode']]
            );

            Setting::updateOrCreate(
                ['key' => 'late_allowance_minutes'],
                ['value' => $data['late_allowance_minutes']]
            );

            Setting::updateOrCreate(
                ['key' => 'early_clock_in_minutes'],
                ['value' => $data['early_clock_in_minutes'] ?? null]
            );

            Setting::updateOrCreate(
                ['key' => 'early_clock_out_minutes'],
                ['value' => $data['early_clock_out_minutes'] ?? null]
            );

            Setting::updateOrCreate(
                ['key' => 'allow_off_day_attendance'],
                ['value' => (bool) ($data['allow_off_day_attendance'] ?? false)]
            );

            Setting::updateOrCreate(
                ['key' => 'break_notification_before_seconds'],
                ['value' => $data['break_mode'] === 'fixed' ? ($data['break_notification_before_seconds'] ?? null) : null]
            );

            Setting::updateOrCreate(
                ['key' => 'early_break_out_minutes'],
                ['value' => $data['break_mode'] === 'fixed' ? ($data['early_break_out_minutes'] ?? null) : null]
            );

            $days = [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ];

            foreach ($days as $day) {

                $working = (bool) ($data["{$day}_working"] ?? false);

                $startTime = $data["{$day}_start_time"] ?? null;
                $endTime = $data["{$day}_end_time"] ?? null;

                $breakEnabled = (bool) ($data["{$day}_break_enabled"] ?? false);

                $breakStart = $data["{$day}_break_start"] ?? null;
                $breakEnd = $data["{$day}_break_end"] ?? null;
                $breakTime = isset($data["{$day}_break_time"]) ? (int) $data["{$day}_break_time"] : null;

                $requiredMinutes = 0;

                if ($working && $startTime && $endTime) {
                    $requiredMinutes = Carbon::parse($endTime)->diffInMinutes(Carbon::parse($startTime));
                    if ($breakEnabled) {
                        if ($data['break_mode'] === 'fixed') {
                            if ($breakStart && $breakEnd) {
                                $requiredMinutes -= Carbon::parse($breakEnd)->diffInMinutes(Carbon::parse($breakStart));
                            }
                        } else {
                            if ($breakTime) {
                                $requiredMinutes -= $breakTime;
                            }
                        }
                    }
                }

                $settings = [
                    "{$day}_working"         => $working,
                    "{$day}_start_time"      => $startTime,
                    "{$day}_end_time"        => $endTime,
                    "{$day}_break_enabled"   => $breakEnabled,
                    "{$day}_break_start"     => $data['break_mode'] === 'fixed' ? $breakStart : null,
                    "{$day}_break_end"       => $data['break_mode'] === 'fixed' ? $breakEnd : null,
                    "{$day}_break_time"      => $data['break_mode'] === 'flexible' ? $breakTime : null,
                    "{$day}_required_minutes" => $requiredMinutes,
                ];

                foreach ($settings as $key => $value) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }
        });

        Cache::forget('settings');
        Cache::forget('work_schedule_settings');
    }
}