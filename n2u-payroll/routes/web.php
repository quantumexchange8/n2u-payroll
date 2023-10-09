<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\MemberController;

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
})->name('login');



// Route::get('index', Controller::class, 'dashboard')->name('index');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login_post', [LoginController::class, 'login_post'])->name('login_post');

Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register_post', [LoginController::class, 'register_post'])->name('register_post');




Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [MemberController::class, 'dashboard'])->name('index');
    Route::post('/clock_in', [RecordController::class, 'clock_in'])->name('clock_in');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});