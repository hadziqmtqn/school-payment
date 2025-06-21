<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\Reference\SchoolYearController;
use App\Http\Controllers\Dashboard\Setting\AccountController;
use App\Http\Controllers\Dashboard\Setting\AdminController;
use App\Http\Controllers\Dashboard\Setting\ApplicationController;
use App\Http\Controllers\Dashboard\Setting\MenuController;
use App\Http\Controllers\Dashboard\Setting\PermissionController;
use App\Http\Controllers\Dashboard\Setting\RoleController;
use App\Http\Controllers\Dashboard\Setting\WhatsappApiConfigController;
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

    // TODO Settings
    Route::prefix('menu')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('menu.index');
        Route::post('/datatable', [MenuController::class, 'datatable']);
        Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/{menu:slug}/show', [MenuController::class, 'show'])->name('menu.show');
        Route::put('/{menu:slug}/update', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/{menu:slug}/delete', [MenuController::class, 'destroy']);
    });

    Route::prefix('application')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('application.index');
        Route::post('/store', [ApplicationController::class, 'store'])->name('application.store');
    });

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::post('/datatable', [RoleController::class, 'datatable']);
        Route::get('/{role:slug}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('/{role:slug}/update', [RoleController::class, 'update'])->name('role.update');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/datatable', [AdminController::class, 'datatable']);
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/{user:username}', [AdminController::class, 'show'])->name('admin.show');
        Route::put('/{user:username}/update', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/{user:username}/delete', [AdminController::class, 'destroy']);
        Route::post('/{user:username}/restore', [AdminController::class, 'restore']);
        Route::delete('/{user:username}/force-delete', [AdminController::class, 'forceDelete']);
    });

    Route::prefix('whatsapp-api-config')->group(function () {
        Route::get('/', [WhatsappApiConfigController::class, 'index'])->name('whatsapp-api-config.index');
        Route::post('/store', [WhatsappApiConfigController::class, 'store'])->name('whatsapp-api-config.store');
    });

    // TODO References
    Route::prefix('school-year')->group(function () {
        Route::get('/', [SchoolYearController::class, 'index'])->name('school-year.index');
        Route::post('/datatable', [SchoolYearController::class, 'datatable']);
        Route::post('/store', [SchoolYearController::class, 'store'])->name('school-year.store');
        Route::put('/{schoolYear:slug}/update', [SchoolYearController::class, 'update']);
    });

    // TODO Select
    Route::get('search-menu', [MenuController::class, 'searchMenu']);
    Route::get('select-main-menu', [MenuController::class, 'select']);
    Route::get('/select-permission', [PermissionController::class, 'select']);

    // TODO Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
