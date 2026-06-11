<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->middleware('permission:employee.view')->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->middleware('permission:employee.create')->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->middleware('permission:employee.create')->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->middleware('permission:employee.view')->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->middleware('permission:employee.edit')->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->middleware('permission:employee.edit')->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->middleware('permission:employee.delete')->name('destroy');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:role.view')->name('index');
        Route::get('/create', [RoleController::class, 'create'])->middleware('permission:role.create')->name('create');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:role.create')->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role.edit')->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:role.edit')->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:role.delete')->name('destroy');
    });

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->middleware('permission:department.view')->name('index');
        Route::post('/', [DepartmentController::class, 'store'])->middleware('permission:department.create')->name('store');
        Route::put('/{department}', [DepartmentController::class, 'update'])->middleware('permission:department.edit')->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:department.delete')->name('destroy');
        Route::post('/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('designations')->name('designations.')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->middleware('permission:designation.view')->name('index');
        Route::post('/', [DesignationController::class, 'store'])->middleware('permission:designation.create')->name('store');
        Route::put('/{designation}', [DesignationController::class, 'update'])->middleware('permission:designation.edit')->name('update');
        Route::delete('/{designation}', [DesignationController::class, 'destroy'])->middleware('permission:designation.delete')->name('destroy');
    });

    Route::prefix('leave-types')->name('leave-types.')->group(function () {
        Route::get('/', [LeaveTypeController::class, 'index'])->middleware('permission:leave_type.view')->name('index');
        Route::post('/', [LeaveTypeController::class, 'store'])->middleware('permission:leave_type.create')->name('store');
        Route::put('/{leave_type}', [LeaveTypeController::class, 'update'])->middleware('permission:leave_type.edit')->name('update');
        Route::delete('/{leave_type}', [LeaveTypeController::class, 'destroy'])->middleware('permission:leave_type.delete')->name('destroy');
    });

    Route::prefix('holidays')->name('holidays.')->group(function () {
        Route::get('/', [HolidayController::class, 'index'])->middleware('permission:holiday.view')->name('index');
        Route::post('/', [HolidayController::class, 'store'])->middleware('permission:holiday.create')->name('store');
        Route::put('/{holiday}', [HolidayController::class, 'update'])->middleware('permission:holiday.edit')->name('update');
        Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->middleware('permission:holiday.delete')->name('destroy');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [SettingController::class, 'general'])->middleware('permission:settings.general.view')->name('general.index');
        Route::get('/work-schedule', [SettingController::class, 'workSchedule'])->middleware('permission:settings.work_schedule.view')->name('work-schedule.index');
        Route::post('/work-schedule', [SettingController::class, 'updateWorkSchedule'])->middleware('permission:settings.work_schedule.edit')->name('work-schedule.update');
    });

    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->middleware('permission:leave.view')->name('index');
        Route::post('/', [LeaveRequestController::class, 'store'])->middleware('permission:leave.create')->name('store');
        Route::get('/{leave}', [LeaveRequestController::class, 'show'])->middleware('permission:leave.view')->name('show');
        Route::put('/{leave}', [LeaveRequestController::class, 'update'])->middleware('permission:leave.edit')->name('update');
        Route::delete('/{leave}', [LeaveRequestController::class, 'destroy'])->middleware('permission:leave.delete')->name('destroy');
        Route::post('/{leave}/status', [LeaveRequestController::class, 'updateStatus'])->middleware('permission:leave.edit')->name('status.update');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->middleware('permission:attendance.view')->name('index');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->middleware('permission:attendance.create')->name('check-in');
        Route::post('/break-start', [AttendanceController::class, 'breakStart'])->middleware('permission:attendance.create')->name('break-start');
        Route::post('/break-end', [AttendanceController::class, 'breakEnd'])->middleware('permission:attendance.create')->name('break-end');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->middleware('permission:attendance.create')->name('check-out');
        Route::get('/{attendance}', [AttendanceController::class, 'show'])->middleware('permission:attendance.view')->name('show');
        Route::put('/{attendance}', [AttendanceController::class, 'update'])->middleware('permission:attendance.edit')->name('update');
        Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->middleware('permission:attendance.delete')->name('destroy');
    });
});

require __DIR__.'/auth.php';
