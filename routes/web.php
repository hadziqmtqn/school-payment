<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\Setting\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('login');
        Route::post('/store', [AuthController::class, 'store'])->name('login.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account.index');
        Route::post('/store', [AccountController::class, 'store'])->name('account.store');
    });

    // TODO Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
