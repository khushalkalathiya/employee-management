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
                ['key' => 'late_allowance_minutes'],
                ['value' => $data['late_allowance_minutes']]
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

                $requiredMinutes = 0;

                if ($working && $startTime && $endTime) {
                    $requiredMinutes = Carbon::parse($endTime)->diffInMinutes(Carbon::parse($startTime));
                    if ($breakEnabled && $breakStart && $breakEnd) {
                        $requiredMinutes -= Carbon::parse($breakEnd)->diffInMinutes(Carbon::parse($breakStart));
                    }
                }

                $settings = [
                    "{$day}_working"         => $working,
                    "{$day}_start_time"      => $startTime,
                    "{$day}_end_time"        => $endTime,
                    "{$day}_break_enabled"   => $breakEnabled,
                    "{$day}_break_start"     => $breakStart,
                    "{$day}_break_end"       => $breakEnd,
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
    }
}