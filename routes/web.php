<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:user.view')->name('index');
        Route::get('/create', [UserController::class, 'create'])->middleware('permission:user.create')->name('create');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:user.create')->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:user.view')->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:user.edit')->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:user.edit')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:user.delete')->name('destroy');
    });
});

require __DIR__.'/auth.php';
