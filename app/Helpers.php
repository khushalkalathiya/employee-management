<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (! function_exists('auth_user')) {

    function auth_user()
    {
        return Auth::user();
    }

}

if (! function_exists('user_id')) {

    function user_id(): ?int
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

if (! function_exists('is_admin')) {

    function is_admin(): bool
    {
        return has_role('Admin');
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