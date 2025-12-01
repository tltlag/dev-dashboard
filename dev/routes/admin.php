<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SyncBexioController;
use App\Http\Controllers\Admin\SyncWildixController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\ConfigurationController;

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'proceedWithLogin'])->name('login.proceed');

    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
    Route::post('/forgot-password', [AuthController::class, 'proceedWithForgotPassword'])->name('forgot_password.procced');

    Route::get('/reset-password/{id}/{hash}', [AuthController::class, 'resetPassword'])->name('reset.password');
    Route::post('/reset-password', [AuthController::class, 'proceedWithResetPassword'])->name('reset.password.procced');

    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [AccountController::class, 'edit'])->name('profile');
        Route::post('/profile', [AccountController::class, 'update'])->name('profile.update');
        Route::get('/profile/image', [AccountController::class, 'profileImage'])->name('profile.image');

        /* Employee Start [ */
        Route::get('/user/employees', [EmployeeController::class, 'index'])->name('user.employee.index');
        Route::get('/user/employee/list', [EmployeeController::class, 'list'])->name('user.employee.list');
        Route::get('/user/employee/search', [EmployeeController::class, 'search'])->name('user.employee.search');
        /* Employee End ] */

        Route::get('/configuration/{group}', [ConfigurationController::class, 'index'])->name('configuration');
        Route::post('/configuration/{group}/save', [ConfigurationController::class, 'save'])->name('configuration.save');

        Route::post('/file/save', [FileController::class, 'save'])->name('file.save');

        Route::get('sync/bexio/contacts', [SyncBexioController::class, 'contacts'])->name('sync.bexio.contacts');
        Route::get('sync/call-history/{id}', [SyncWildixController::class, 'callHistory'])->name('sync.wildix.calls');

        Route::resource('translations', TranslationController::class);
        Route::get('/translation/list', [TranslationController::class, 'list'])->name('translation.list');
        Route::get('/translation/sync', [TranslationController::class, 'sync'])->name('translations.sync');
    });
});
