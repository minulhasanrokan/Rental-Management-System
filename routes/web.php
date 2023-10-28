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
use App\Http\Controllers\BloodGroupController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\UserConfigController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UnitRentInformationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TagOwnerController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RentProcessController;
use App\Http\Controllers\VatTaxController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ComplainController;

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

Route::controller(CommonController::class)->group(function(){

    Route::get('/dashboard/get-duplicate-value/{field_name?}/{table_name?}/{value?}/{data_id?}','get_duplicate_value')->name('admin.get.duplicate.value');

    Route::get('/dashboard/get-duplicate-value-two/{field_name?}/{field_name2?}/{table_name?}/{value?}/{value2?}/{data_id?}/{other_status?}/{other_value?}','get_duplicate_value_two')->name('admin.get.duplicate.value.two');

    Route::get('/dashboard/get-parameter-data/{table_name?}/{field_name?}/{value?}','get_parameter_data')->name('admin.get.parameter.data');
    Route::get('/dashboard/get-parameter-data-by-id/{table_name?}/{field_name?}/{value?}/{data_value?}/{data_name?}/{other_status?}/{other_value?}','get_parameter_data_by_id')->name('admin.get.parameter.data.by.id');

    Route::get('/dashboard/get-data-by-id/{table_name?}/{data_id?}/{field_name?}','get_data_by_id')->name('admin.get.data.by.id');

    Route::get('/dashboard/get-current-tenant-info-by-unit/{value?}','current_tenant_info_by_unit')->name('admin.get.current.tenant.info.by.unit');
});

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

    Route::get('/change-password','change_password_page')->name('admin.change.password')->middleware(['changepassword']);
    Route::post('/change-password','change_password_store')->name('admin.change.password')->middleware(['changepassword']);
});

Route::controller(LoginController::class)->group(function(){

    Route::get('/login','login_page')->name('admin.login')->middleware(['logincheck']);
    Route::post('/login-store','login_store')->name('admin.login.store');
});

Route::controller(DashboardController::class)->group(function(){

    Route::get('/dashboard','dashboard')->name('admin.dashboard')->middleware(['dashboardcheck']);
    Route::get('/dashboard/logout','logout')->name('admin.logout')->middleware(['dashboardcheck']);
});

Route::controller(SystemSettingController::class)->group(function(){

    Route::get('/dashboard/system-setting-information','system_setting_page')->name('system_setting.information.add')->middleware(['checkroute']);
    Route::post('/dashboard/system-setting-information','system_information_update')->name('system_setting.information.add')->middleware(['checkroute']);

    Route::get('/dashboard/system-setting-mail','system_mail_page')->name('system_setting.mail.add')->middleware(['checkroute']);
    Route::post('/dashboard/system-setting-mail','system_mail_update')->name('system_setting.mail.add')->middleware(['checkroute']);
});

Route::controller(UserConfigController::class)->group(function(){

    Route::get('/dashboard/user-config','user_config_page')->name('system_setting.user_config.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-config','user_config_update')->name('system_setting.user_config.add')->middleware(['checkroute']);
});

Route::controller(UserGroupController::class)->group(function(){

    Route::get('/dashboard/user-group-add','user_group_add_page')->name('user_management.user_group.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-add','user_group_store')->name('user_management.user_group.add')->middleware(['checkroute']);

    Route::get('/dashboard/user-group-view','user_group_view_page')->name('user_management.user_group.view')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-view','user_group_grid')->name('user_management.user_group.view')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-view/{id?}','user_group_single_view_page')->name('user_management.user_group.view')->middleware(['checkroute']);

    Route::get('/dashboard/user-group-edit','user_group_edit_page')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-edit','user_group_grid')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-edit/{id?}','user_group_single_edit_page')->name('user_management.user_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-edit/{id?}','user_group_update')->name('user_management.user_group.edit')->middleware(['checkroute']);

    Route::get('/dashboard/user-group-delete','user_group_delete_page')->name('user_management.user_group.delete')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-delete','user_group_grid')->name('user_management.user_group.delete')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-delete/{id?}','user_group_delete')->name('user_management.user_group.delete')->middleware(['checkroute']);

    Route::get('/dashboard/user-group-right','user_group_right_page')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-right','user_group_grid')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::get('/dashboard/user-group-right/{id?}','user_group_right_setup_page')->name('user_management.user_group.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-group-right/{id?}','user_group_right_store')->name('user_management.user_group.right')->middleware(['checkroute']);
});

Route::controller(UserController::class)->group(function(){

    Route::get('/dashboard/user-add','user_add_page')->name('user_management.user.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-add','user_store')->name('user_management.user.add')->middleware(['checkroute']);

    Route::get('/dashboard/user-edit','user_edit_page')->name('user_management.user.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-edit','user_grid')->name('user_management.user.edit')->middleware(['checkroute']);
    Route::get('/dashboard/user-edit/{id?}','user_single_edit_page')->name('user_management.user.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-edit/{id?}','user_update')->name('user_management.user.edit')->middleware(['checkroute']);

    Route::get('/dashboard/user-delete','user_delete_page')->name('user_management.user.delete')->middleware(['checkroute']);
    Route::post('/dashboard/user-delete','user_grid')->name('user_management.user.delete')->middleware(['checkroute']);
    Route::get('/dashboard/user-delete/{id?}','user_delete')->name('user_management.user.delete')->middleware(['checkroute']);

    Route::get('/dashboard/user-view','user_view_page')->name('user_management.user.view')->middleware(['checkroute']);
    Route::post('/dashboard/user-view','user_grid')->name('user_management.user.view')->middleware(['checkroute']);
    Route::get('/dashboard/user-view/{id?}','user_single_view_page')->name('user_management.user.view')->middleware(['checkroute']);

    Route::get('/dashboard/user-right','user_right_page')->name('user_management.user.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-right','user_grid')->name('user_management.user.right')->middleware(['checkroute']);
    Route::get('/dashboard/user-right/{id?}','user_right_setup_page')->name('user_management.user.right')->middleware(['checkroute']);
    Route::post('/dashboard/user-right/{id?}','user_right_store')->name('user_management.user.right')->middleware(['checkroute']);
});

Route::controller(EmployeeController::class)->group(function(){

    Route::get('/dashboard/employee-add','employee_add_page')->name('user_management.employee.add')->middleware(['checkroute']);
    Route::post('/dashboard/employee-add','employee_store')->name('user_management.employee.add')->middleware(['checkroute']);

    Route::get('/dashboard/employee-edit','employee_edit_page')->name('user_management.employee.edit')->middleware(['checkroute']);
    Route::post('/dashboard/employee-edit','employee_grid')->name('user_management.employee.edit')->middleware(['checkroute']);
    Route::get('/dashboard/employee-edit/{id?}','employee_single_edit_page')->name('user_management.employee.edit')->middleware(['checkroute']);
    Route::post('/dashboard/employee-edit/{id?}','employee_update')->name('user_management.employee.edit')->middleware(['checkroute']);

    Route::get('/dashboard/employee-delete','employee_delete_page')->name('user_management.employee.delete')->middleware(['checkroute']);
    Route::post('/dashboard/employee-delete','employee_grid')->name('user_management.employee.delete')->middleware(['checkroute']);
    Route::get('/dashboard/employee-delete/{id?}','employee_delete')->name('user_management.employee.delete')->middleware(['checkroute']);

    Route::get('/dashboard/employee-view','employee_view_page')->name('user_management.employee.view')->middleware(['checkroute']);
    Route::post('/dashboard/employee-view','employee_grid')->name('user_management.employee.view')->middleware(['checkroute']);
    Route::get('/dashboard/employee-view/{id?}','employee_single_view_page')->name('user_management.employee.view')->middleware(['checkroute']);

    Route::get('/dashboard/employee-right','employee_right_page')->name('user_management.employee.right')->middleware(['checkroute']);
    Route::post('/dashboard/employee-right','employee_grid')->name('user_management.employee.right')->middleware(['checkroute']);
    Route::get('/dashboard/employee-right/{id?}','employee_right_setup_page')->name('user_management.employee.right')->middleware(['checkroute']);
    Route::post('/dashboard/employee-right/{id?}','employee_right_store')->name('user_management.employee.right')->middleware(['checkroute']);

});

Route::controller(OwnerController::class)->group(function(){

    Route::get('/dashboard/owner-add','owner_add_page')->name('user_management.owner.add')->middleware(['checkroute']);
    Route::post('/dashboard/owner-add','owner_store')->name('user_management.owner.add')->middleware(['checkroute']);

    Route::get('/dashboard/owner-edit','owner_edit_page')->name('user_management.owner.edit')->middleware(['checkroute']);
    Route::post('/dashboard/owner-edit','owner_grid')->name('user_management.owner.edit')->middleware(['checkroute']);
    Route::get('/dashboard/owner-edit/{id?}','owner_single_edit_page')->name('user_management.owner.edit')->middleware(['checkroute']);
    Route::post('/dashboard/owner-edit/{id?}','owner_update')->name('user_management.owner.edit')->middleware(['checkroute']);

    Route::get('/dashboard/owner-delete','owner_delete_page')->name('user_management.owner.delete')->middleware(['checkroute']);
    Route::post('/dashboard/owner-delete','owner_grid')->name('user_management.owner.delete')->middleware(['checkroute']);
    Route::get('/dashboard/owner-delete/{id?}','owner_delete')->name('user_management.owner.delete')->middleware(['checkroute']);

    Route::get('/dashboard/owner-view','owner_view_page')->name('user_management.owner.view')->middleware(['checkroute']);
    Route::post('/dashboard/owner-view','owner_grid')->name('user_management.owner.view')->middleware(['checkroute']);
    Route::get('/dashboard/owner-view/{id?}','owner_single_view_page')->name('user_management.owner.view')->middleware(['checkroute']);

    Route::get('/dashboard/owner-right','owner_right_page')->name('user_management.owner.right')->middleware(['checkroute']);
    Route::post('/dashboard/owner-right','owner_grid')->name('user_management.owner.right')->middleware(['checkroute']);
    Route::get('/dashboard/owner-right/{id?}','owner_right_setup_page')->name('user_management.owner.right')->middleware(['checkroute']);
    Route::post('/dashboard/owner-right/{id?}','owner_right_store')->name('user_management.owner.right')->middleware(['checkroute']);

});

Route::controller(TenantController::class)->group(function(){

    Route::get('/dashboard/tenant-add','tenant_add_page')->name('user_management.tenant.add')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-add','tenant_store')->name('user_management.tenant.add')->middleware(['checkroute']);

    Route::get('/dashboard/tenant-edit','tenant_edit_page')->name('user_management.tenant.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-edit','tenant_grid')->name('user_management.tenant.edit')->middleware(['checkroute']);
    Route::get('/dashboard/tenant-edit/{id?}','tenant_single_edit_page')->name('user_management.tenant.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-edit/{id?}','tenant_update')->name('user_management.tenant.edit')->middleware(['checkroute']);

    Route::get('/dashboard/tenant-delete','tenant_delete_page')->name('user_management.tenant.delete')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-delete','tenant_grid')->name('user_management.tenant.delete')->middleware(['checkroute']);
    Route::get('/dashboard/tenant-delete/{id?}','tenant_delete')->name('user_management.tenant.delete')->middleware(['checkroute']);

    Route::get('/dashboard/tenant-view','tenant_view_page')->name('user_management.tenant.view')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-view','tenant_grid')->name('user_management.tenant.view')->middleware(['checkroute']);
    Route::get('/dashboard/tenant-view/{id?}','tenant_single_view_page')->name('user_management.tenant.view')->middleware(['checkroute']);

    Route::get('/dashboard/tenant-right','tenant_right_page')->name('user_management.tenant.right')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-right','tenant_grid')->name('user_management.tenant.right')->middleware(['checkroute']);
    Route::get('/dashboard/tenant-right/{id?}','tenant_right_setup_page')->name('user_management.tenant.right')->middleware(['checkroute']);
    Route::post('/dashboard/tenant-right/{id?}','tenant_right_store')->name('user_management.tenant.right')->middleware(['checkroute']);

});

Route::controller(GenderController::class)->group(function(){

    Route::get('/dashboard/gender-add','gender_add_page')->name('reference_data.gender.add')->middleware(['checkroute']);
    Route::post('/dashboard/gender-add','gender_add_store')->name('reference_data.gender.add')->middleware(['checkroute']);

    Route::get('/dashboard/gender-edit','gender_edit_page')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::post('/dashboard/gender-edit','gender_grid')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::get('/dashboard/gender-edit/{id?}','gender_single_edit_page')->name('reference_data.gender.edit')->middleware(['checkroute']);
    Route::post('/dashboard/gender-edit/{id?}','gender_update')->name('reference_data.gender.edit')->middleware(['checkroute']);

    Route::get('/dashboard/gender-view','gender_view_page')->name('reference_data.gender.view')->middleware(['checkroute']);
    Route::post('/dashboard/gender-view','gender_grid')->name('reference_data.gender.view')->middleware(['checkroute']);
    Route::get('/dashboard/gender-view/{id?}','gender_single_view_page')->name('reference_data.gender.view')->middleware(['checkroute']);

    Route::get('/dashboard/gender-delete','gender_delete_page')->name('reference_data.gender.delete')->middleware(['checkroute']);
    Route::post('/dashboard/gender-delete','gender_grid')->name('reference_data.gender.delete')->middleware(['checkroute']);
    Route::get('/dashboard/gender-delete/{id?}','gender_delete')->name('reference_data.gender.delete')->middleware(['checkroute']);
});

Route::controller(BloodGroupController::class)->group(function(){

    Route::get('/dashboard/blood-group-add','blood_group_add_page')->name('reference_data.blood_group.add')->middleware(['checkroute']);
    Route::post('/dashboard/blood-group-add','blood_group_store')->name('reference_data.blood_group.add')->middleware(['checkroute']);

    Route::get('/dashboard/blood-group-edit','blood_group_edit_page')->name('reference_data.blood_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/blood-group-edit','blood_group_grid')->name('reference_data.blood_group.edit')->middleware(['checkroute']);
    Route::get('/dashboard/blood-group-edit/{id?}','blood_group_single_edit_page')->name('reference_data.blood_group.edit')->middleware(['checkroute']);
    Route::post('/dashboard/blood-group-edit/{id?}','blood_group_update')->name('reference_data.blood_group.edit')->middleware(['checkroute']);

    Route::get('/dashboard/blood-group-view','blood_group_view_page')->name('reference_data.blood_group.view')->middleware(['checkroute']);
    Route::post('/dashboard/blood-group-view','blood_group_grid')->name('reference_data.blood_group.view')->middleware(['checkroute']);
    Route::get('/dashboard/blood-group-view/{id?}','blood_group_single_view_page')->name('reference_data.blood_group.view')->middleware(['checkroute']);

    Route::get('/dashboard/blood-group-delete','blood_group_delete_page')->name('reference_data.blood_group.delete')->middleware(['checkroute']);
    Route::post('/dashboard/blood-group-delete','blood_group_grid')->name('reference_data.blood_group.delete')->middleware(['checkroute']);
    Route::get('/dashboard/blood-group-delete/{id?}','blood_group_delete')->name('reference_data.blood_group.delete')->middleware(['checkroute']);
});

Route::controller(DesignationController::class)->group(function(){

    Route::get('/dashboard/designation-add','designation_add_page')->name('reference_data.designation.add')->middleware(['checkroute']);
    Route::post('/dashboard/designation-add','designation_store')->name('reference_data.designation.add')->middleware(['checkroute']);

    Route::get('/dashboard/designation-edit','designation_edit_page')->name('reference_data.designation.edit')->middleware(['checkroute']);
    Route::post('/dashboard/designation-edit','designation_grid')->name('reference_data.designation.edit')->middleware(['checkroute']);
    Route::get('/dashboard/designation-edit/{id?}','designation_single_edit_page')->name('reference_data.designation.edit')->middleware(['checkroute']);
    Route::post('/dashboard/designation-edit/{id?}','designation_update')->name('reference_data.designation.edit')->middleware(['checkroute']);

    Route::get('/dashboard/designation-view','designation_view_page')->name('reference_data.designation.view')->middleware(['checkroute']);
    Route::post('/dashboard/designation-view','designation_grid')->name('reference_data.designation.view')->middleware(['checkroute']);
    Route::get('/dashboard/designation-view/{id?}','designation_single_view_page')->name('reference_data.designation.view')->middleware(['checkroute']);

    Route::get('/dashboard/designation-delete','designation_delete_page')->name('reference_data.designation.delete')->middleware(['checkroute']);
    Route::post('/dashboard/designation-delete','designation_grid')->name('reference_data.designation.delete')->middleware(['checkroute']);
    Route::get('/dashboard/designation-delete/{id?}','designation_delete')->name('reference_data.designation.delete')->middleware(['checkroute']);
});

Route::controller(DepartmentController::class)->group(function(){

    Route::get('/dashboard/department-add','department_add_page')->name('reference_data.department.add')->middleware(['checkroute']);
    Route::post('/dashboard/department-add','department_store')->name('reference_data.department.add')->middleware(['checkroute']);

    Route::get('/dashboard/department-edit','department_edit_page')->name('reference_data.department.edit')->middleware(['checkroute']);
    Route::post('/dashboard/department-edit','department_grid')->name('reference_data.department.edit')->middleware(['checkroute']);
    Route::get('/dashboard/department-edit/{id?}','department_single_edit_page')->name('reference_data.department.edit')->middleware(['checkroute']);
    Route::post('/dashboard/department-edit/{id?}','department_update')->name('reference_data.department.edit')->middleware(['checkroute']);

    Route::get('/dashboard/department-view','department_view_page')->name('reference_data.department.view')->middleware(['checkroute']);
    Route::post('/dashboard/department-view','department_grid')->name('reference_data.department.view')->middleware(['checkroute']);
    Route::get('/dashboard/department-view/{id?}','department_single_view_page')->name('reference_data.department.view')->middleware(['checkroute']);

    Route::get('/dashboard/department-delete','department_delete_page')->name('reference_data.department.delete')->middleware(['checkroute']);
    Route::post('/dashboard/department-delete','department_grid')->name('reference_data.department.delete')->middleware(['checkroute']);
    Route::get('/dashboard/department-delete/{id?}','department_delete')->name('reference_data.department.delete')->middleware(['checkroute']);
});

Route::controller(UserTypeController::class)->group(function(){

    Route::get('/dashboard/user-type-add','user_type_add_page')->name('reference_data.user.type.add')->middleware(['checkroute']);
    Route::post('/dashboard/user-type-add','user_type_store')->name('reference_data.user.type.add')->middleware(['checkroute']);

    Route::get('/dashboard/user-type-edit','user_type_edit_page')->name('reference_data.user.type.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-type-edit','user_type_grid')->name('reference_data.user.type.edit')->middleware(['checkroute']);
    Route::get('/dashboard/user-type-edit/{id?}','user_type_single_edit_page')->name('reference_data.user.type.edit')->middleware(['checkroute']);
    Route::post('/dashboard/user-type-edit/{id?}','user_type_update')->name('reference_data.user.type.edit')->middleware(['checkroute']);

    Route::get('/dashboard/user-type-view','user_type_view_page')->name('reference_data.user.type.view')->middleware(['checkroute']);
    Route::post('/dashboard/user-type-view','user_type_grid')->name('reference_data.user.type.view')->middleware(['checkroute']);
    Route::get('/dashboard/user-type-view/{id?}','user_type_single_view_page')->name('reference_data.user.type.view')->middleware(['checkroute']);

    Route::get('/dashboard/user-type-delete','user_type_delete_page')->name('reference_data.user.type.delete')->middleware(['checkroute']);
    Route::post('/dashboard/user-type-delete','user_type_grid')->name('reference_data.user.type.delete')->middleware(['checkroute']);
    Route::get('/dashboard/user-type-delete/{id?}','user_type_delete')->name('reference_data.user.type.delete')->middleware(['checkroute']);
});

Route::controller(BuildingController::class)->group(function(){

    Route::get('/dashboard/building-add','building_add_page')->name('floor_management.building.add')->middleware(['checkroute']);
    Route::post('/dashboard/building-add','building_store')->name('floor_management.building.add')->middleware(['checkroute']);

    Route::get('/dashboard/building-edit','building_edit_page')->name('floor_management.building.edit')->middleware(['checkroute']);
    Route::post('/dashboard/building-edit','building_grid')->name('floor_management.building.edit')->middleware(['checkroute']);
    Route::get('/dashboard/building-edit/{id?}','building_single_edit_page')->name('floor_management.building.edit')->middleware(['checkroute']);
    Route::post('/dashboard/building-edit/{id?}','building_update')->name('floor_management.building.edit')->middleware(['checkroute']);

    Route::get('/dashboard/building-delete','building_delete_page')->name('floor_management.building.delete')->middleware(['checkroute']);
    Route::post('/dashboard/building-delete','building_grid')->name('floor_management.building.delete')->middleware(['checkroute']);
    Route::get('/dashboard/building-delete/{id?}','building_delete')->name('floor_management.building.delete')->middleware(['checkroute']);

    Route::get('/dashboard/building-view','building_view_page')->name('floor_management.building.view')->middleware(['checkroute']);
    Route::post('/dashboard/building-view','building_grid')->name('floor_management.building.view')->middleware(['checkroute']);
    Route::get('/dashboard/building-view/{id?}','building_single_view_page')->name('floor_management.building.view')->middleware(['checkroute']);
});

Route::controller(LevelController::class)->group(function(){

    Route::get('/dashboard/level-add','level_add_page')->name('floor_management.level.add')->middleware(['checkroute']);
    Route::post('/dashboard/level-add','level_store')->name('floor_management.level.add')->middleware(['checkroute']);

    Route::get('/dashboard/level-edit','level_edit_page')->name('floor_management.level.edit')->middleware(['checkroute']);
    Route::post('/dashboard/level-edit','level_grid')->name('floor_management.level.edit')->middleware(['checkroute']);
    Route::get('/dashboard/level-edit/{id?}','level_single_edit_page')->name('floor_management.level.edit')->middleware(['checkroute']);
    Route::post('/dashboard/level-edit/{id?}','level_update')->name('floor_management.level.edit')->middleware(['checkroute']);

    Route::get('/dashboard/level-delete','level_delete_page')->name('floor_management.level.delete')->middleware(['checkroute']);
    Route::post('/dashboard/level-delete','level_grid')->name('floor_management.level.delete')->middleware(['checkroute']);
    Route::get('/dashboard/level-delete/{id?}','level_delete')->name('floor_management.level.delete')->middleware(['checkroute']);

    Route::get('/dashboard/level-view','level_view_page')->name('floor_management.level.view')->middleware(['checkroute']);
    Route::post('/dashboard/level-view','level_grid')->name('floor_management.level.view')->middleware(['checkroute']);
    Route::get('/dashboard/level-view/{id?}','level_single_view_page')->name('floor_management.level.view')->middleware(['checkroute']);
});

Route::controller(UnitController::class)->group(function(){

    Route::get('/dashboard/unit-add','unit_add_page')->name('floor_management.unit.add')->middleware(['checkroute']);
    Route::post('/dashboard/unit-add','unit_store')->name('floor_management.unit.add')->middleware(['checkroute']);

    Route::get('/dashboard/unit-edit','unit_edit_page')->name('floor_management.unit.edit')->middleware(['checkroute']);
    Route::post('/dashboard/unit-edit','unit_grid')->name('floor_management.unit.edit')->middleware(['checkroute']);
    Route::get('/dashboard/unit-edit/{id?}','unit_single_edit_page')->name('floor_management.unit.edit')->middleware(['checkroute']);
    Route::post('/dashboard/unit-edit/{id?}','unit_update')->name('floor_management.unit.edit')->middleware(['checkroute']);

    Route::get('/dashboard/unit-delete','unit_delete_page')->name('floor_management.unit.delete')->middleware(['checkroute']);
    Route::post('/dashboard/unit-delete','unit_grid')->name('floor_management.unit.delete')->middleware(['checkroute']);
    Route::get('/dashboard/unit-delete/{id?}','unit_delete')->name('floor_management.unit.delete')->middleware(['checkroute']);

    Route::get('/dashboard/unit-view','unit_view_page')->name('floor_management.unit.view')->middleware(['checkroute']);
    Route::post('/dashboard/unit-view','unit_grid')->name('floor_management.unit.view')->middleware(['checkroute']);
    Route::get('/dashboard/unit-view/{id?}','unit_single_view_page')->name('floor_management.unit.view')->middleware(['checkroute']);
});

Route::controller(UnitRentInformationController::class)->group(function(){

    Route::get('/dashboard/unit-rent-information-add','unit_rent_add_page')->name('floor_management.unit_rent.add')->middleware(['checkroute']);
    Route::post('/dashboard/unit-rent-information-add','unit_rent_store')->name('floor_management.unit_rent.add')->middleware(['checkroute']);

    Route::get('/dashboard/unit-rent-information-edit','unit_rent_edit_page')->name('floor_management.unit_rent.edit')->middleware(['checkroute']);
    Route::post('/dashboard/unit-rent-information-edit','unit_rent_grid')->name('floor_management.unit_rent.edit')->middleware(['checkroute']);
    Route::get('/dashboard/unit-rent-information-edit/{id?}','unit_rent_single_edit_page')->name('floor_management.unit_rent.edit')->middleware(['checkroute']);
    Route::post('/dashboard/unit-rent-information-edit/{id?}','unit_rent_update')->name('floor_management.unit_rent.edit')->middleware(['checkroute']);

    Route::get('/dashboard/unit-rent-information-delete','unit_rent_delete_page')->name('floor_management.unit_rent.delete')->middleware(['checkroute']);
    Route::post('/dashboard/unit-rent-information-delete','unit_rent_grid')->name('floor_management.unit_rent.delete')->middleware(['checkroute']);
    Route::get('/dashboard/unit-rent-information-delete/{id?}','unit_rent_delete')->name('floor_management.unit_rent.delete')->middleware(['checkroute']);

    Route::get('/dashboard/unit-rent-information-view','unit_rent_view_page')->name('floor_management.unit_rent.view')->middleware(['checkroute']);
    Route::post('/dashboard/unit-rent-information-view','unit_rent_grid')->name('floor_management.unit_rent.view')->middleware(['checkroute']);
    Route::get('/dashboard/unit-rent-information-view/{id?}','unit_rent_single_view_page')->name('floor_management.unit_rent.view')->middleware(['checkroute']);

});

Route::controller(TagOwnerController::class)->group(function(){

    Route::get('/dashboard/tag-owner-unit-add','tag_owner_add_page')->name('floor_management.tag_unit_owner.add')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-add','tag_owner_store')->name('floor_management.tag_unit_owner.add')->middleware(['checkroute']);

    Route::get('/dashboard/tag-owner-unit-edit','tag_owner_edit_page')->name('floor_management.tag_unit_owner.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-edit','tag_owner_grid')->name('floor_management.tag_unit_owner.edit')->middleware(['checkroute']);
    Route::get('/dashboard/tag-owner-unit-edit/{id?}','tag_owner_single_edit_page')->name('floor_management.tag_unit_owner.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-edit/{id?}','tag_owner_update')->name('floor_management.tag_unit_owner.edit')->middleware(['checkroute']);

    Route::get('/dashboard/tag-owner-unit-delete','tag_owner_delete_page')->name('floor_management.tag_unit_owner.delete')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-delete','tag_owner_grid')->name('floor_management.tag_unit_owner.delete')->middleware(['checkroute']);
    Route::get('/dashboard/tag-owner-unit-delete/{id?}','tag_owner_delete')->name('floor_management.tag_unit_owner.delete')->middleware(['checkroute']);

    Route::get('/dashboard/tag-owner-unit-view','tag_owner_view_page')->name('floor_management.tag_unit_owner.view')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-view','tag_owner_grid')->name('floor_management.tag_unit_owner.view')->middleware(['checkroute']);
    Route::get('/dashboard/tag-owner-unit-view/{id?}','tag_owner_single_view_page')->name('floor_management.tag_unit_owner.view')->middleware(['checkroute']);

    Route::get('/dashboard/tag-owner-unit-transfer','tag_owner_transfer_page')->name('floor_management.tag_unit_owner.transfer')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-transfer','tag_owner_grid')->name('floor_management.tag_unit_owner.transfer')->middleware(['checkroute']);
    Route::get('/dashboard/tag-owner-unit-transfer/{id?}','tag_owner_transfer')->name('floor_management.tag_unit_owner.transfer')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-unit-transfer/{id?}','tag_owner_transfer_update')->name('floor_management.tag_unit_owner.transfer')->middleware(['checkroute']);

    Route::get('/dashboard/tag-owner-transfer-view','tag_owner_transfer_view_page')->name('floor_management.tag_unit_owner.transfer_view')->middleware(['checkroute']);
    Route::post('/dashboard/tag-owner-transfer-view','tag_owner_transfer_grid')->name('floor_management.tag_unit_owner.transfer_view')->middleware(['checkroute']);
    Route::get('/dashboard/tag-owner-transfer-view/{id?}','tag_owner_transfer_single_view_page')->name('floor_management.tag_unit_owner.transfer_view')->middleware(['checkroute']);

});

Route::controller(RentalController::class)->group(function(){

    Route::get('/dashboard/rental-add','unit_rent_add_page')->name('rent_management.rental.add')->middleware(['checkroute']);
    Route::post('/dashboard/rental-add','unit_rent_store')->name('rent_management.rental.add')->middleware(['checkroute']);

    Route::get('/dashboard/get-rent-info-value/{field_name?}','get_rent_info_value')->name('rent_management.get.rent_info.value');

    Route::get('/dashboard/rental-view','rental_view_page')->name('rent_management.rental.view')->middleware(['checkroute']);
    Route::post('/dashboard/rental-view','rental_grid')->name('rent_management.rental.view')->middleware(['checkroute']);
    Route::get('/dashboard/rental-view/{id?}','rental_single_view_page')->name('rent_management.rental.view')->middleware(['checkroute']);

    Route::get('/dashboard/rental-edit','rental_edit_page')->name('rent_management.rental.edit')->middleware(['checkroute']);
    Route::post('/dashboard/rental-edit','rental_grid')->name('rent_management.rental.edit')->middleware(['checkroute']);
    Route::get('/dashboard/rental-edit/{id?}','rental_single_edit_page')->name('rent_management.rental.edit')->middleware(['checkroute']);
    Route::post('/dashboard/rental-edit/{id?}','rental_update')->name('rent_management.rental.edit')->middleware(['checkroute']);

    Route::get('/dashboard/rental-delete','rental_delete_page')->name('rent_management.rental.delete')->middleware(['checkroute']);
    Route::post('/dashboard/rental-delete','rental_grid')->name('rent_management.rental.delete')->middleware(['checkroute']);
    Route::get('/dashboard/rental-delete/{id?}','rental_delete')->name('rent_management.rental.delete')->middleware(['checkroute']);

    Route::get('/dashboard/rental-change-rent','rental_change_page')->name('rent_management.rental.change')->middleware(['checkroute']);
    Route::post('/dashboard/rental-change-rent','rental_grid')->name('rent_management.rental.change')->middleware(['checkroute']);
    Route::get('/dashboard/rental-change-rent/{id?}','rental_single_change_page')->name('rent_management.rental.change')->middleware(['checkroute']);
    Route::post('/dashboard/rental-change-rent/{id?}','rental_change')->name('rent_management.rental.change')->middleware(['checkroute']);

    Route::get('/dashboard/rental-close','rental_close_page')->name('rent_management.rental.close')->middleware(['checkroute']);
    Route::post('/dashboard/rental-close','rental_grid')->name('rent_management.rental.close')->middleware(['checkroute']);
    Route::get('/dashboard/rental-close/{id?}','rental_single_close_page')->name('rent_management.rental.close')->middleware(['checkroute']);
    Route::post('/dashboard/rental-close/{id?}','rent_close_update')->name('rent_management.rental.close')->middleware(['checkroute']);

});

Route::controller(RentProcessController::class)->group(function(){

    Route::get('/dashboard/rental-process-add','rent_process_add_page')->name('rent_management.process.add')->middleware(['checkroute']);
    Route::post('/dashboard/rental-process-add','rent_process_store')->name('rent_management.process.add')->middleware(['checkroute']);

    Route::get('/dashboard/rent-process-view','rent_process_view_page')->name('rent_management.process.view')->middleware(['checkroute']);
    Route::post('/dashboard/rent-process-view','rent_process_grid')->name('rent_management.process.view')->middleware(['checkroute']);
    Route::get('/dashboard/rent-process-view/{id?}','rent_process_single_view_page')->name('rent_management.process.view')->middleware(['checkroute']);

    Route::get('/dashboard/rent-process-invoice','rent_invoice_view_page')->name('rent_management.process.invoice')->middleware(['checkroute']);
    Route::post('/dashboard/rent-process-invoice','rent_invoice_grid')->name('rent_management.process.invoice')->middleware(['checkroute']);
    Route::get('/dashboard/rent-process-invoice/{id?}','rent_invoice_single_view_page')->name('rent_management.process.invoice')->middleware(['checkroute']);

    Route::get('/dashboard/rent-process-print/{id?}/{status?}','rent_invoice_single_print')->name('rent_management.process.print');

    Route::get('/dashboard/rent-collection-add','rent_collection_add_page')->name('rent_management.process.rent_collection')->middleware(['checkroute']);
    Route::post('/dashboard/rent-collection-add','rent_process_grid')->name('rent_management.process.rent_collection')->middleware(['checkroute']);
    Route::get('/dashboard/rent-collection-add/{id?}','rent_collection_single_edit_page')->name('rent_management.process.rent_collection')->middleware(['checkroute']);
    Route::post('/dashboard/rent-collection-add/{id?}','rent_collection_update')->name('rent_management.process.rent_collection')->middleware(['checkroute']);
});

Route::controller(VatTaxController::class)->group(function(){

    Route::get('/dashboard/tax-add','tax_add_page')->name('reference_data.tax.add')->middleware(['checkroute']);
    Route::post('/dashboard/tax-add','tax_store')->name('reference_data.tax.add')->middleware(['checkroute']);

    Route::get('/dashboard/tax-view','tax_view_page')->name('reference_data.tax.view')->middleware(['checkroute']);
    Route::post('/dashboard/tax-view','tax_grid')->name('reference_data.tax.view')->middleware(['checkroute']);
    Route::get('/dashboard/tax-view/{id?}','tax_single_view_page')->name('reference_data.tax.view')->middleware(['checkroute']);

    Route::get('/dashboard/tax-edit','tax_edit_page')->name('reference_data.tax.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tax-edit','tax_grid')->name('reference_data.tax.edit')->middleware(['checkroute']);
    Route::get('/dashboard/tax-edit/{id?}','tax_single_edit_page')->name('reference_data.tax.edit')->middleware(['checkroute']);
    Route::post('/dashboard/tax-edit/{id?}','tax_update')->name('reference_data.tax.edit')->middleware(['checkroute']);

    Route::get('/dashboard/tax-delete','tax_delete_page')->name('reference_data.tax.delete')->middleware(['checkroute']);
    Route::post('/dashboard/tax-delete','tax_grid')->name('reference_data.tax.delete')->middleware(['checkroute']);
    Route::get('/dashboard/tax-delete/{id?}','tax_delete')->name('reference_data.tax.delete')->middleware(['checkroute']);

});

Route::controller(ComplainController::class)->group(function(){

    Route::get('/dashboard/complain-add','complain_add_page')->name('complain_management.manage.add')->middleware(['checkroute']);
    Route::post('/dashboard/complain-add','complain_store')->name('complain_management.manage.add')->middleware(['checkroute']);

    Route::get('/dashboard/complain-view','complain_view_page')->name('complain_management.manage.view')->middleware(['checkroute']);
    Route::post('/dashboard/complain-view','complain_grid')->name('complain_management.manage.view')->middleware(['checkroute']);
    Route::get('/dashboard/complain-view/{id?}','complain_single_view_page')->name('complain_management.manage.view')->middleware(['checkroute']);

    Route::get('/dashboard/complain-user','assign_page')->name('complain_management.manage.assign')->middleware(['checkroute']);
    Route::post('/dashboard/complain-user','complain_grid')->name('complain_management.manage.assign')->middleware(['checkroute']);
    Route::get('/dashboard/complain-user/{id?}','assign_single_page')->name('complain_management.manage.assign')->middleware(['checkroute']);
    Route::post('/dashboard/complain-user/{id?}','assign_add')->name('complain_management.manage.assign')->middleware(['checkroute']);

    Route::get('/dashboard/complain-status','status_page')->name('complain_management.manage.status')->middleware(['checkroute']);
    Route::post('/dashboard/complain-status','complain_status_grid')->name('complain_management.manage.status')->middleware(['checkroute']);
    Route::get('/dashboard/complain-status/{id?}','status_single_page')->name('complain_management.manage.status')->middleware(['checkroute']);
    Route::post('/dashboard/complain-status/{id?}','status_add')->name('complain_management.manage.status')->middleware(['checkroute']);

    Route::get('/dashboard/complain-edit','edit_page')->name('complain_management.manage.edit')->middleware(['checkroute']);
    Route::post('/dashboard/complain-edit','complain_grid')->name('complain_management.manage.edit')->middleware(['checkroute']);
    Route::get('/dashboard/complain-edit/{id?}','edit_single_page')->name('complain_management.manage.edit')->middleware(['checkroute']);
    Route::post('/dashboard/complain-edit/{id?}','edit_add')->name('complain_management.manage.edit')->middleware(['checkroute']);

});

Route::controller(VisitorController::class)->group(function(){

    Route::get('/dashboard/visitor-add','visitor_add_page')->name('visitor_management.visitor.add')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-add','visitor_store')->name('visitor_management.visitor.add')->middleware(['checkroute']);

    Route::get('/dashboard/visitor-view','visitor_view_page')->name('visitor_management.visitor.view')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-view','visitor_grid')->name('visitor_management.visitor.view')->middleware(['checkroute']);
    Route::get('/dashboard/visitor-view/{id?}','visitor_single_view_page')->name('visitor_management.visitor.view')->middleware(['checkroute']);

    Route::get('/dashboard/visitor-edit','visitor_edit_page')->name('visitor_management.visitor.edit')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-edit','visitor_grid')->name('visitor_management.visitor.edit')->middleware(['checkroute']);
    Route::get('/dashboard/visitor-edit/{id?}','visitor_single_edit_page')->name('visitor_management.visitor.edit')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-edit/{id?}','visitor_update')->name('visitor_management.visitor.edit')->middleware(['checkroute']);

    Route::get('/dashboard/visitor-delete','visitor_delete_page')->name('visitor_management.visitor.delete')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-delete','visitor_grid')->name('visitor_management.visitor.delete')->middleware(['checkroute']);
    Route::get('/dashboard/visitor-delete/{id?}','visitor_delete')->name('visitor_management.visitor.delete')->middleware(['checkroute']);

    Route::get('/dashboard/visitor-out','visitor_out_page')->name('visitor_management.visitor.out')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-out','visitor_grid')->name('visitor_management.visitor.out')->middleware(['checkroute']);
    Route::get('/dashboard/visitor-out/{id?}','visitor_single_out_page')->name('visitor_management.visitor.out')->middleware(['checkroute']);
    Route::post('/dashboard/visitor-out/{id?}','visitor_out')->name('visitor_management.visitor.out')->middleware(['checkroute']);

    Route::get('/dashboard/my-visitor-view','my_visitor_view_page')->name('visitor_management.my_visitor.view')->middleware(['checkroute']);
    Route::post('/dashboard/my-visitor-view','my_visitor_grid')->name('visitor_management.my_visitor.view')->middleware(['checkroute']);
    Route::get('/dashboard/my-visitor-view/{id?}','my_visitor_single_view_page')->name('visitor_management.my_visitor.view')->middleware(['checkroute']);
});
