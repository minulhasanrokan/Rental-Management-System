<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenderController;

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

// common route..............
Route::controller(CommonController::class)->group(function(){

    Route::get('/dashboard/get-duplicate-value/{field_name?}/{table_name?}/{value?}/{data_id?}','get_duplicate_value')->name('admin.get.duplicate.value')->middleware(['checkroute']);
});

// registration route..................
Route::controller(RegistrationController::class)->group(function(){

    Route::get('/registration','registration_page')->name('admin.registration')->middleware(['logincheck']);
    Route::post('/registration-store','registration_store')->name('admin.registration.store');
    Route::get('/resend-verify-email/{token}','resend_verify_email')->name('admin.resend.verify.email')->middleware(['logincheck']);

    Route::get('/verify-email/{token}','verify_email_page')->name('admin.verify.email');

    Route::get('/forgot-password','forgot_password_page')->name('admin.forgot.password')->middleware(['logincheck']);
    Route::post('/forgot-password','forgot_password_link')->name('admin.forgot.password');
    Route::get('/resend-forgot-password/{email}','resend_forgot_password')->name('admin.forgot.resend.password')->middleware(['logincheck']);
    Route::get('/reset-password/{email}','reset_password_page')->name('admin.reset.password');
    Route::post('/reset-password/{email}','reset_password_store')->name('admin.reset.password');
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

// user group route..............
Route::controller(UserGroupController::class)->group(function(){

    Route::get('/dashboard/user-group-add','user_group_add_page')->name('user_management.user_group.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-add','user_group_store')->name('user_management.user_group.add')->middleware(['checkroute']);

    // for all view user group route........
    Route::get('/dashboard/user-group-view','user_group_view_page')->name('user_management.user_group.view')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-view','user_group_grid')->name('user_management.user_group.view')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-view/{id?}','user_group_single_view_page')->name('user_management.user_group.view')->middleware(['checkroute']);

    // for all edit view user group route........
    Route::get('/dashboard/user-group-edit','user_group_edit_page')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-edit','user_group_grid')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-edit/{id?}','user_group_single_edit_page')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-edit/{id?}','user_group_update')->name('user_management.user_group.edit')->middleware(['checkroute']);

    // for all delete view user group route........
    Route::get('/dashboard/user-group-delete','user_group_delete_page')->name('user_management.user_group.delete')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-delete','user_group_grid')->name('user_management.user_group.delete')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-delete/{id?}','user_group_delete')->name('user_management.user_group.delete')->middleware(['checkroute']);

    // for all group set right view user group route........
    Route::get('/dashboard/user-group-right','user_group_right_page')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-right','user_group_grid')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-right/{id?}','user_group_right_setup_page')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-right/{id?}','user_group_right_store')->name('user_management.user_group.right')->middleware(['checkroute']);
});


// user route..............
Route::controller(UserController::class)->group(function(){

    Route::get('/dashboard/user-add','user_add_page')->name('user_management.user.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-add','user_group_store')->name('user_management.user.add')->middleware(['checkroute']);

});


// all refarence route.......

// Gender route..............
Route::controller(GenderController::class)->group(function(){

    Route::get('/dashboard/gender-add','gender_add_page')->name('reference_data.gender.add')->middleware(['checkroute']);
    Route::post('/dashboard/gender-add','gender_add_store')->name('reference_data.gender.add')->middleware(['checkroute']);

    // for all edit view Gender route........
    Route::get('/dashboard/gender-edit','gender_edit_page')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::post('/dashboard/gender-edit','gender_grid')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::get('/dashboard/gender-edit/{id?}','gender_single_edit_page')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::post('/dashboard/gender-edit/{id?}','gender_update')->name('reference_data.gender.edit')->middleware(['checkroute']);

    // for all view gender route........
    Route::get('/dashboard/gender-view','gender_view_page')->name('reference_data.gender.view')->middleware(['checkroute']);
    Route::post('/dashboard/gender-view','gender_grid')->name('reference_data.gender.view')->middleware(['checkroute']);
    Route::get('/dashboard/gender-view/{id?}','gender_single_view_page')->name('reference_data.gender.view')->middleware(['checkroute']);

    // for all delete view gender route........
    Route::get('/dashboard/gender-delete','gender_delete_page')->name('reference_data.gender.delete')->middleware(['checkroute']);
    Route::post('/dashboard/gender-delete','gender_grid')->name('reference_data.gender.delete')->middleware(['checkroute']);
    Route::get('/dashboard/gender-delete/{id?}','gender_delete')->name('reference_data.gender.delete')->middleware(['checkroute']);

});
