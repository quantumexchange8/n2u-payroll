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
    Route::get('dashboard', [MemberController::class, 'dashboard'])->name('index');
    Route::post('/clock_in', [RecordController::class, 'clock_in'])->name('clock_in');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('admin/dashboard', [AdminController::class, 'Admindashboard'])->name('admindashboard');

    Route::get('admin/viewEmployee', [AdminController::class, 'viewEmployee'])->name('viewEmployee');
    Route::get('admin/createEmployee', [AdminController::class, 'createEmployee'])->name('createEmployee');
    Route::post('admin/addEmployee', [AdminController::class, 'addEmployee'])->name('addEmployee');
    Route::get('admin/editEmployee/{id}', [AdminController::class, 'editEmployee'])->name('editEmployee');
    Route::put('admin/updateEmployee/{id}', [AdminController::class, 'updateEmployee'])->name('updateEmployee');
    Route::delete('admin/deleteEmployee/{id}', [AdminController::class, 'deleteEmployee'])->name('deleteEmployee');

    Route::get('admin/viewPosition', [AdminController::class, 'viewPosition'])->name('viewPosition');
    Route::get('admin/createPosition', [AdminController::class, 'createPosition'])->name('createPosition');
    Route::post('admin/addPosition', [AdminController::class, 'addPosition'])->name('addPosition');
    Route::get('admin/editPosition/{id}', [AdminController::class, 'editPosition'])->name('editPosition');
    Route::put('admin/updatePosition/{id}', [AdminController::class, 'updatePosition'])->name('updatePosition');
    Route::delete('admin/deletePosition/{id}', [AdminController::class, 'deletePosition'])->name('deletePosition');

});