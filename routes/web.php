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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login_post', [LoginController::class, 'login_post'])->name('login_post');

Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register_post', [LoginController::class, 'register_post'])->name('register_post');




Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [MemberController::class, 'dashboard'])->name('homepage');
    Route::post('/clock_in', [RecordController::class, 'clock_in'])->name('clock_in');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('admin/dashboard', [AdminController::class, 'Admindashboard'])->name('admindashboard');

    Route::get('admin/viewEmployee', [AdminController::class, 'viewEmployee'])->name('viewEmployee');
    Route::get('admin/createEmployee', [AdminController::class, 'createEmployee'])->name('createEmployee');
    Route::post('admin/addEmployee', [AdminController::class, 'addEmployee'])->name('addEmployee');
    Route::get('admin/editEmployee/{id}', [AdminController::class, 'editEmployee'])->name('editEmployee');
    Route::post('admin/updateEmployee/{id}', [AdminController::class, 'updateEmployee'])->name('updateEmployee');
    Route::post('admin/updateEmployeePassword/{id}', [AdminController::class, 'updateEmployeePassword'])->name('updateEmployeePassword');
    Route::delete('admin/deleteEmployee/{id}', [AdminController::class, 'deleteEmployee'])->name('deleteEmployee');

    Route::get('admin/viewPosition', [AdminController::class, 'viewPosition'])->name('viewPosition');
    Route::get('admin/createPosition', [AdminController::class, 'createPosition'])->name('createPosition');
    Route::post('admin/addPosition', [AdminController::class, 'addPosition'])->name('addPosition');
    Route::get('admin/editPosition/{id}', [AdminController::class, 'editPosition'])->name('editPosition');
    Route::post('admin/updatePosition/{id}', [AdminController::class, 'updatePosition'])->name('updatePosition');
    Route::delete('admin/deletePosition/{id}', [AdminController::class, 'deletePosition'])->name('deletePosition');

    Route::get('admin/viewDepartment', [AdminController::class, 'viewDepartment'])->name('viewDepartment');
    Route::get('admin/createDepartment', [AdminController::class, 'createDepartment'])->name('createDepartment');
    Route::post('admin/addDepartment', [AdminController::class, 'addDepartment'])->name('addDepartment');
    Route::get('admin/editDepartment/{id}', [AdminController::class, 'editDepartment'])->name('editDepartment');
    Route::post('admin/updateDepartment/{id}', [AdminController::class, 'updateDepartment'])->name('updateDepartment');
    Route::delete('admin/deleteDepartment/{id}', [AdminController::class, 'deleteDepartment'])->name('deleteDepartment');

    Route::get('admin/viewDuty', [AdminController::class, 'viewDuty'])->name('viewDuty');
    Route::get('admin/createDuty', [AdminController::class, 'createDuty'])->name('createDuty');
    Route::post('admin/addDuty', [AdminController::class, 'addDuty'])->name('addDuty');
    Route::get('admin/editDuty/{id}', [AdminController::class, 'editDuty'])->name('editDuty');
    Route::post('admin/updateDuty/{id}', [AdminController::class, 'updateDuty'])->name('updateDuty');
    Route::delete('admin/deleteDuty/{id}', [AdminController::class, 'deleteDuty'])->name('deleteDuty');

    Route::get('admin/viewShift', [AdminController::class, 'viewShift'])->name('viewShift');
    Route::get('admin/createShift', [AdminController::class, 'createShift'])->name('createShift');
    Route::post('admin/addShift', [AdminController::class, 'addShift'])->name('addShift');
    Route::get('admin/editShift/{id}', [AdminController::class, 'editShift'])->name('editShift');
    Route::post('admin/updateShift/{id}', [AdminController::class, 'updateShift'])->name('updateShift');
    Route::delete('admin/deleteShift/{id}', [AdminController::class, 'deleteShift'])->name('deleteShift');

    Route::get('admin/schedule', [AdminController::class, 'schedule'])->name('schedule');
    Route::post('admin/addSchedule', [AdminController::class, 'addSchedule'])->name('addSchedule');
    Route::post('admin/updateSchedule/{id}', [AdminController::class, 'updateSchedule'])->name('updateSchedule');
    Route::delete('admin/deleteSchedule/{id}', [AdminController::class, 'deleteSchedule'])->name('deleteSchedule');

    Route::get('admin/viewSetting', [AdminController::class, 'viewSetting'])->name('viewSetting');
    Route::get('admin/createSetting', [AdminController::class, 'createSetting'])->name('createSetting');
    Route::post('admin/addSetting', [AdminController::class, 'addSetting'])->name('addSetting');
    Route::get('admin/editSetting/{id}', [AdminController::class, 'editSetting'])->name('editSetting');
    Route::post('admin/updateSetting/{id}', [AdminController::class, 'updateSetting'])->name('updateSetting');
    Route::delete('admin/deleteSetting/{id}', [AdminController::class, 'deleteSetting'])->name('deleteSetting');

    Route::get('admin/otApproval', [AdminController::class, 'otApproval'])->name('otApproval');
    Route::post('admin/updateOtApproval/{id}', [AdminController::class, 'updateOtApproval'])->name('updateOtApproval');
    Route::delete('admin/deleteOtApproval/{id}', [AdminController::class, 'deleteOtApproval'])->name('deleteOtApproval');

    // User

    Route::get('user/viewSchedule', [MemberController::class, 'viewSchedule'])->name('viewSchedule');

    Route::get('user/viewProfile', [MemberController::class, 'viewProfile'])->name('viewProfile');
    Route::post('user/updateProfile', [MemberController::class, 'updateProfile'])->name('updateProfile');
});