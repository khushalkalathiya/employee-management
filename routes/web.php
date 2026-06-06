<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
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
});

require __DIR__.'/auth.php';
