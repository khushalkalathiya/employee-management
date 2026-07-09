<?php

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (! function_exists('auth_user')) {

    function auth_user()
    {
        return Auth::user();
    }

}

if (! function_exists('authId')) {

    function authId(): ?int
    {
        return Auth::id();
    }

}

if (! function_exists('has_permission')) {

    function has_permission(string $permission): bool
    {
        return Auth::user()?->can($permission) ?? false;
    }

}

if (! function_exists('has_role')) {

    function has_role(string $role): bool
    {
        return Auth::user()?->hasRole($role) ?? false;
    }

}

if (! function_exists('dateFormat')) {
    function dateFormat(mixed $date, string $format = 'd M Y') {
        try {
            return Carbon::parse($date)->format($format);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
if (! function_exists('timeFormat')) {
    function timeFormat(mixed $time, string $format = 'h:i A') {
        try {
            return Carbon::parse($time)->format($format);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
if (! function_exists('convertTimeTo24HourFormat')) {
    function convertTimeTo24HourFormat(?string $time): string
    {
        try {
            return \Carbon\Carbon::createFromFormat('g:i A', $time)
                ->format('H:i');
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (! function_exists('numberFormat')) {
    function numberFormat(mixed $amount): string
    {
        try {
            return number_format((float)$amount, 2);
        } catch (\Throwable $e) {
            return '0.00';
        }
    }
}

if (! function_exists('currencyFormat')) {
    function currencyFormat(mixed $amount): string
    {
        try {
            return "$".numberFormat($amount);
        } catch (\Throwable $e) {
            return '$0.00';
        }
    }
}

if (!function_exists('get_work_schedule_settings')) {
    function get_work_schedule_settings() {
        return Cache::rememberForever('work_schedule_settings', function () {
            $keys = [
                'timing_mode', 'late_allowance_minutes', 'early_clock_in_minutes',
                'early_clock_out_minutes', 'allow_off_day_attendance',
                'break_mode', 'break_notification_before_seconds',
            ];
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $keys[] = "{$day}_working";
                $keys[] = "{$day}_start_time";
                $keys[] = "{$day}_end_time";
                $keys[] = "{$day}_break_enabled";
                $keys[] = "{$day}_break_start";
                $keys[] = "{$day}_break_end";
            }
            return Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        });
    }
}
