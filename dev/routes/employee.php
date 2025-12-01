<?php

use App\Http\Controllers\Employee\AuthController;
use App\Http\Controllers\Employee\CallHistoryController;
use App\Http\Controllers\Employee\ContactController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\SyncBexioController;
use App\Http\Controllers\Employee\TimeLogController;
use App\Http\Controllers\Employee\WorkLogController;
use Illuminate\Support\Facades\Route;

Route::name('employee.')
    ->prefix('employee')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');

        /** Dashboard [ */
        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
        /** Dashboard ] */

        /** Call History [ */
        Route::get('/call/history', [CallHistoryController::class, 'index'])
        ->name('call.history');
        Route::get('/call/history/list', [CallHistoryController::class, 'list'])
        ->name('call.history.list');
        // Route::get('/call/history/delete/{call_history}', [CallHistoryController::class, 'deleteCallHistory'])
        //     ->name('call.history.delete');
        Route::get('/call/log/popup/{id}', [CallHistoryController::class, 'popup'])
        ->name('call.log.popup');
        Route::get('/call/log/popup', [CallHistoryController::class, 'globalPopup'])
        ->name('call.log.global-popup');

        Route::get('/clockodo/projects', [CallHistoryController::class, 'clockoDoProjects'])
        ->name('clockodo.projects');

        Route::post('/call/log/store/{id}', [CallHistoryController::class, 'store'])
        ->name('call.log.store');
        Route::post('/call/log/store', [CallHistoryController::class, 'globalLogTime'])
        ->name('call.log.global-store');
        Route::get('/call/bexio/redirect/{phone_number}', [CallHistoryController::class, 'bexioRedirect'])
        ->name('call.bexio.redirect');
        Route::get('/call/logs/{id?}', [CallHistoryController::class, 'logs'])
        ->name('call.logs');
        Route::get('/call/log/list/{id?}', [CallHistoryController::class, 'logList'])
        ->name('call.log.list');
        Route::get('/call/log/description/{id}', [CallHistoryController::class, 'logDesc'])
        ->name('call.log.desc');

        Route::get('/call/store-history', [CallHistoryController::class, 'storeHistory'])
        ->name('call.store.history');
        /** Call History ] */

        /** Contact [ */
        Route::get('/contacts', [ContactController::class, 'index'])
        ->name('contacts');
        Route::get('/contact/list', [ContactController::class, 'list'])
        ->name('contact.list');
        Route::get('/contact/add', [ContactController::class, 'form'])
        ->name('contact.add');
        Route::get('/contact/edit/{bexio_employee:id}', [ContactController::class, 'form'])
        ->name('contact.edit');
        Route::post('/contact/store/{bexio_employee:id?}', [ContactController::class, 'store'])
        ->name('contact.store');
        Route::get('/search/contact', [ContactController::class, 'searchch'])
        ->name('contact.searchch');

        Route::get('/contact/assign/{bexio_employee:id}', [ContactController::class, 'assignPage'])
        ->name('contact.assign');
        Route::get('/search/contacts/{bexio_employee:id}', [ContactController::class, 'searchContacts'])
        ->name('contact.search');
        Route::post('/link/contacts/{bexio_employee:id}', [ContactController::class, 'link'])
        ->name('contact.link');
        Route::get('/link/contact/remove/{otherId}/{bexio_employee:id}', [ContactController::class, 'linkRemove'])
        ->name('contact.link.remove');
        Route::get('/delete/contact/{bexio_employee:id}', [ContactController::class, 'deleteContact'])
        ->name('contact.delete');
        Route::get('/contact/get-companies', [ContactController::class, 'getCompanies'])
        ->name('contact.getCompanies');
        // Create routes to get company and employee form this would be ajax request
        Route::get('/contact/form/employee', [ContactController::class, 'employeeForm'])
            ->name('contact.form.employee');
        Route::get('/contact/form/company/list', [ContactController::class, 'companyListForm'])
            ->name('contact.form.company.list');
        Route::get('/contact/form/company/complete', [ContactController::class, 'companyForm'])
            ->name('contact.form.company.complete');
        /** Contact ] */

        Route::get('sync/bexio/contacts', [SyncBexioController::class, 'contacts'])
        ->name('sync.bexio.contacts');
        Route::get('sync/bexio/company/popup', [SyncBexioController::class, 'companyPopup'])
        ->name('bexio.company.popup');
        Route::post('sync/bexio/company/add', [SyncBexioController::class, 'addCompany'])
        ->name('bexio.company.add');

        Route::get('/time-logs', [TimeLogController::class, 'getTimeLogs'])
        ->name('call.time-logs');
        Route::get('/time/log/delete/{time_log}', [TimeLogController::class, 'deleteTimeLog'])
        ->name('clockodo.log.delete');

        Route::get('/work-log', [WorkLogController::class, 'workLog'])->name('work.log');
        Route::get('/work-log/entries', [WorkLogController::class, 'workLogEntries'])->name('work.log.entries');
    });
