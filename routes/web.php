<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\Reference\ClassLevelController;
use App\Http\Controllers\Dashboard\Reference\SchoolYearController;
use App\Http\Controllers\Dashboard\Reference\SubClassLevelController;
use App\Http\Controllers\Dashboard\Setting\AccountController;
use App\Http\Controllers\Dashboard\Setting\AdminController;
use App\Http\Controllers\Dashboard\Setting\ApplicationController;
use App\Http\Controllers\Dashboard\Setting\MenuController;
use App\Http\Controllers\Dashboard\Setting\MessageTemplateController;
use App\Http\Controllers\Dashboard\Setting\PermissionController;
use App\Http\Controllers\Dashboard\Setting\RoleController;
use App\Http\Controllers\Dashboard\Setting\WhatsappApiConfigController;
use App\Http\Controllers\Dashboard\Student\ImportNewStudentController;
use App\Http\Controllers\Dashboard\Student\PromotedToNextGradeController;
use App\Http\Controllers\Dashboard\Student\StudentController;
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

    Route::prefix('student')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('student.index');
        Route::post('/datatable', [StudentController::class, 'datatable']);
        Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        Route::get('/{user:username}/show', [StudentController::class, 'show'])->name('student.show');
        Route::put('/{user:username}/update', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/{user:username}/delete', [StudentController::class, 'destroy']);
        Route::post('/{username}/restore', [StudentController::class, 'restore']);
        Route::delete('/{username}/permanently-delete', [StudentController::class, 'permanentlyDelete']);
    });

    Route::post('student-import', [ImportNewStudentController::class, 'store'])->name('import-new-student.store');

    Route::prefix('promoted-to-next-grade')->group(function () {
        Route::get('/', [PromotedToNextGradeController::class, 'index'])->name('promoted-to-next-grade.index');
        Route::post('/datatable', [PromotedToNextGradeController::class, 'datatable']);
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

    Route::prefix('message-template')->group(function () {
        Route::get('/', [MessageTemplateController::class, 'index'])->name('message-template.index');
        Route::post('/store', [MessageTemplateController::class, 'store'])->name('message-template.store');
        Route::get('/{messageTemplate:slug}', [MessageTemplateController::class, 'show'])->name('message-template.show');
        Route::put('/{messageTemplate:slug}/update', [MessageTemplateController::class, 'update'])->name('message-template.update');
        Route::delete('/{messageTemplate:slug}/delete', [MessageTemplateController::class, 'destroy'])->name('message-template.destroy');
    });

    // TODO References
    Route::prefix('school-year')->group(function () {
        Route::get('/', [SchoolYearController::class, 'index'])->name('school-year.index');
        Route::post('/datatable', [SchoolYearController::class, 'datatable']);
        Route::post('/store', [SchoolYearController::class, 'store'])->name('school-year.store');
        Route::put('/{schoolYear:slug}/update', [SchoolYearController::class, 'update']);
    });

    Route::prefix('class-level')->group(function () {
        Route::get('/', [ClassLevelController::class, 'index'])->name('class-level.index');
        Route::post('/datatable', [ClassLevelController::class, 'datatable']);
        Route::post('/store', [ClassLevelController::class, 'store'])->name('class-level.store');
        Route::put('/{classLevel:slug}/update', [ClassLevelController::class, 'update']);
        Route::delete('/{classLevel:slug}/delete', [ClassLevelController::class, 'destroy']);
    });

    Route::prefix('sub-class-level')->group(function () {
        Route::post('/datatable', [SubClassLevelController::class, 'datatable']);
        Route::post('/store', [SubClassLevelController::class, 'store'])->name('sub-class-level.store');
        Route::put('/{subClassLevel:slug}/update', [SubClassLevelController::class, 'update']);
        Route::delete('/{subClassLevel:slug}/delete', [SubClassLevelController::class, 'destroy']);
    });

    // TODO Select
    Route::get('search-menu', [MenuController::class, 'searchMenu']);
    Route::get('select-main-menu', [MenuController::class, 'select']);
    Route::get('select-permission', [PermissionController::class, 'select']);
    Route::get('select-school-year', [SchoolYearController::class, 'select']);
    Route::get('select-class-level', [ClassLevelController::class, 'select']);
    Route::get('select-sub-class-level', [SubClassLevelController::class, 'select']);

    // TODO Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
