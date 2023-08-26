<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// registration route..................
Route::controller(RegistrationController::class)->group(function(){

    Route::get('/registration','registration_page')->name('admin.registration')->middleware(['logincheck']);
    Route::post('/registration-store','registration_store')->name('admin.registration.store');

    Route::get('/verify-email/{token}','verify_email_page')->name('admin.verify.email');
});

// login route..................
Route::controller(LoginController::class)->group(function(){

    Route::get('/login','login_page')->name('admin.login')->middleware(['logincheck']);
    Route::post('/login-store','login_store')->name('admin.login.store');
});

// dashboard route..............
Route::controller(DashboardController::class)->group(function(){

    Route::get('/dashboard','dashboard')->name('admin.dashboard')->middleware(['dashboardcheck']);
    Route::get('/dashboard/logout','logout')->name('admin.logout')->middleware(['dashboardcheck']);
});


// dashboard route..............
Route::controller(SystemSettingController::class)->group(function(){

    Route::get('/dashboard/system-setting-information','system_setting_page')->name('system_setting.information.add')->middleware(['checkroute']);
    Route::post('/dashboard/system-setting-information','system_information_update')->name('system_setting.information.add')->middleware(['checkroute']);

    // mail setup ............
    Route::get('/dashboard/system-setting-mail','system_mail_page')->name('system_setting.mail')->middleware(['checkroute']);
    Route::post('/dashboard/system-setting-mail','system_mail_update')->name('system_setting.mail')->middleware(['checkroute']);
});