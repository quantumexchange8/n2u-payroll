<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;

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

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/admin-login', [LoginController::class, 'adminLogin'])->name('adminLogin');
Route::post('/login_post', [LoginController::class, 'login_post'])->name('login_post');

Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register_post', [LoginController::class, 'register_post'])->name('register_post');

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [MemberController::class, 'dashboard'])->name('homepage');
    Route::get('get-tabledata', [MemberController::class, 'getdata'])->name('getdata');
    Route::post('/clock_in', [RecordController::class, 'clock_in'])->name('clock_in');
    Route::post('/updateStatus', [RecordController::class, 'updateStatus'])->name('updateStatus');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('admin/dashboard', [AdminController::class, 'Admindashboard'])->name('admindashboard');

    Route::get('admin/view-employee', [AdminController::class, 'viewEmployee'])->name('viewEmployee');
    Route::get('admin/create-employee', [AdminController::class, 'createEmployee'])->name('createEmployee');
    Route::post('admin/add-employee', [AdminController::class, 'addEmployee'])->name('addEmployee');
    Route::get('admin/edit-employee/{id}', [AdminController::class, 'editEmployee'])->name('editEmployee');
    Route::post('admin/update-employee/{id}', [AdminController::class, 'updateEmployee'])->name('updateEmployee');
    Route::post('admin/update-employee-password/{id}', [AdminController::class, 'updateEmployeePassword'])->name('updateEmployeePassword');
    Route::delete('admin/delete-employee/{id}', [AdminController::class, 'deleteEmployee'])->name('deleteEmployee');

    Route::get('admin/view-position', [AdminController::class, 'viewPosition'])->name('viewPosition');
    Route::get('admin/create-position', [AdminController::class, 'createPosition'])->name('createPosition');
    Route::post('admin/add-position', [AdminController::class, 'addPosition'])->name('addPosition');
    Route::get('admin/edit-position/{id}', [AdminController::class, 'editPosition'])->name('editPosition');
    Route::post('admin/update-position/{id}', [AdminController::class, 'updatePosition'])->name('updatePosition');
    Route::delete('admin/delete-position/{id}', [AdminController::class, 'deletePosition'])->name('deletePosition');

    Route::get('admin/view-department', [AdminController::class, 'viewDepartment'])->name('viewDepartment');
    Route::get('admin/create-department', [AdminController::class, 'createDepartment'])->name('createDepartment');
    Route::post('admin/add-department', [AdminController::class, 'addDepartment'])->name('addDepartment');
    Route::get('admin/edit-department/{id}', [AdminController::class, 'editDepartment'])->name('editDepartment');
    Route::post('admin/update-department/{id}', [AdminController::class, 'updateDepartment'])->name('updateDepartment');
    Route::delete('admin/delete-department/{id}', [AdminController::class, 'deleteDepartment'])->name('deleteDepartment');

    Route::get('admin/view-duty', [AdminController::class, 'viewDuty'])->name('viewDuty');
    Route::get('admin/create-duty', [AdminController::class, 'createDuty'])->name('createDuty');
    Route::post('admin/add-duty', [AdminController::class, 'addDuty'])->name('addDuty');
    Route::get('admin/edit-duty/{id}', [AdminController::class, 'editDuty'])->name('editDuty');
    Route::post('admin/update-duty/{id}', [AdminController::class, 'updateDuty'])->name('updateDuty');
    Route::delete('admin/delete-duty/{id}', [AdminController::class, 'deleteDuty'])->name('deleteDuty');

    Route::get('admin/view-shift', [AdminController::class, 'viewShift'])->name('viewShift');
    Route::get('admin/create-shift', [AdminController::class, 'createShift'])->name('createShift');
    Route::post('admin/add-shift', [AdminController::class, 'addShift'])->name('addShift');
    Route::get('admin/edit-shift/{id}', [AdminController::class, 'editShift'])->name('editShift');
    Route::post('admin/update-shift/{id}', [AdminController::class, 'updateShift'])->name('updateShift');
    Route::delete('admin/delete-shift/{id}', [AdminController::class, 'deleteShift'])->name('deleteShift');

    Route::get('admin/schedule', [AdminController::class, 'schedule'])->name('schedule');
    Route::get('admin/get-schedule', [AdminController::class, 'getSchedule'])->name('getSchedule');
    Route::get('admin/create-schedule', [AdminController::class, 'createSchedule'])->name('createSchedule');
    Route::post('admin/add-schedule', [AdminController::class, 'addSchedule'])->name('addSchedule');
    Route::get('admin/edit-schedule/{id}', [AdminController::class, 'editSchedule'])->name('editSchedule');
    Route::post('admin/update-schedule/{id}', [AdminController::class, 'updateSchedule'])->name('updateSchedule');
    Route::delete('admin/delete-schedule/{id}', [AdminController::class, 'deleteSchedule'])->name('deleteSchedule');

    Route::get('admin/schedule-report', [AdminController::class, 'scheduleReport'])->name('scheduleReport');
    Route::delete('admin/delete-schedule2/{id}', [AdminController::class, 'deleteSchedule2'])->name('deleteSchedule2');

    Route::get('admin/view-task', [AdminController::class, 'viewTask'])->name('viewTask');
    Route::get('admin/create-task', [AdminController::class, 'createTask'])->name('createTask');
    Route::post('admin/add-task', [AdminController::class, 'addTask'])->name('addTask');
    Route::get('admin/edit-task/{id}', [AdminController::class, 'editTask'])->name('editTask');
    Route::post('admin/update-task/{id}', [AdminController::class, 'updateTask'])->name('updateTask');
    Route::delete('admin/delete-task/{id}', [AdminController::class, 'deleteTask'])->name('deleteTask');

    Route::get('admin/view-period', [AdminController::class, 'viewPeriod'])->name('viewPeriod');
    Route::get('admin/create-period', [AdminController::class, 'createPeriod'])->name('createPeriod');
    Route::post('admin/add-period', [AdminController::class, 'addPeriod'])->name('addPeriod');
    Route::get('admin/edit-period/{id}', [AdminController::class, 'editPeriod'])->name('editPeriod');
    Route::post('admin/update-period/{id}', [AdminController::class, 'updatePeriod'])->name('updatePeriod');
    Route::delete('admin/delete-period/{id}', [AdminController::class, 'deletePeriod'])->name('deletePeriod');

    Route::get('admin/view-setting', [AdminController::class, 'viewSetting'])->name('viewSetting');
    Route::get('admin/create-setting', [AdminController::class, 'createSetting'])->name('createSetting');
    Route::post('admin/add-setting', [AdminController::class, 'addSetting'])->name('addSetting');
    Route::get('admin/edit-setting/{id}', [AdminController::class, 'editSetting'])->name('editSetting');
    Route::post('admin/update-setting/{id}', [AdminController::class, 'updateSetting'])->name('updateSetting');
    Route::delete('admin/delete-setting/{id}', [AdminController::class, 'deleteSetting'])->name('deleteSetting');

    Route::get('admin/view-admin', [AdminController::class, 'viewAdmin'])->name('viewAdmin');
    Route::get('admin/create-admin', [AdminController::class, 'createAdmin'])->name('createAdmin');
    Route::post('admin/add-admin', [AdminController::class, 'addAdmin'])->name('addAdmin');
    Route::get('admin/edit-admin/{id}', [AdminController::class, 'editAdmin'])->name('editAdmin');
    Route::post('admin/update-admin/{id}', [AdminController::class, 'updateAdmin'])->name('updateAdmin');
    Route::post('admin/update-admin-password/{id}', [AdminController::class, 'updateAdminPassword'])->name('updateAdminPassword');
    Route::delete('admin/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('deleteAdmin');

    Route::get('admin/ot-approval', [AdminController::class, 'otApproval'])->name('otApproval');
    Route::post('admin/update-ot-approval/{id}', [AdminController::class, 'updateOtApproval'])->name('updateOtApproval');
    Route::delete('admin/delete-ot-approval/{id}', [AdminController::class, 'deleteOtApproval'])->name('deleteOtApproval');
    Route::get('/get-ot-hour/{id}', [AdminController::class, 'getOtHour']);


    Route::get('admin/salary-logs', [AdminController::class, 'salaryLogs'])->name('salaryLogs');

    Route::get('admin/attendance', [AdminController::class, 'attendance'])->name('attendance');

    Route::get('admin/total-work', [AdminController::class, 'totalWork'])->name('totalWork');
    Route::post('admin/update-total-work/{id}', [AdminController::class, 'updateTotalWork'])->name('updateTotalWork');

    Route::get('admin/other-image/{employee_id}', [AdminController::class, 'otherImage'])->name('otherImage');
    Route::post('admin/other-image/add-other-image/{employeeId}', [AdminController::class, 'addOtherImage'])->name('addOtherImage');
    Route::delete('admin/other-images/{employeeId}/delete/{imageId}', [AdminController::class, 'deleteOtherImage'])->name('deleteOtherImage');


    // User
    Route::get('user/view-schedule', [MemberController::class, 'viewSchedule'])->name('viewSchedule');
    Route::get('user/get-tasks', [MemberController::class, 'getTasks'])->name('getTasks');

    Route::get('user/view-profile', [MemberController::class, 'viewProfile'])->name('viewProfile');
    Route::post('user/update-profile', [MemberController::class, 'updateProfile'])->name('updateProfile');
});

Route::get('/offline', function () {

    return view('modules/laravelpwa/offline');

    });
