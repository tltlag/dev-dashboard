<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Front\FileController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Employee\AuthController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Employee\CallHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [AuthController::class, 'login'])->name('home');
Route::get('/auth/wildixin/{user_id}', [AuthController::class, 'wildixinLogin'])->name('wildixin.login');
Route::get('/auth/wildixin/extension/{extension_id}', [AuthController::class, 'wildixinExtensionLogin'])->name('wildixin.extension.login');
// http://intreation.local/auth/access-dashboard/18/+41792123845
// http://intreation.webtools.co.in/auth/access-dashboard/18/+41792123845
Route::get('/auth/access-dashboard/{extension_id}/{phone_number}', [AuthController::class, 'ongoingCallLogin'])->name('auth.ongoing.call');
Route::get('/images/{filename}', [ImageController::class, 'show'])->name('images.show');


Route::get('/toggle-theme', function () {

    if (auth('employee')->check()) {
        $newTheme =  (auth('employee')->user()->theme ?? 'darkTheme') === 'darkTheme' ? 'lightTheme' : 'darkTheme';
        auth('employee')->user()->update(['theme' => $newTheme]);
    }
    if (auth('admin')->check()) {
        $newTheme =  (auth('admin')->user()->theme ?? 'darkTheme') === 'darkTheme' ? 'lightTheme' : 'darkTheme';
        auth('admin')->user()->update(['theme' => $newTheme]);
    }
    return redirect()->back();
})->name('toggle.theme');
