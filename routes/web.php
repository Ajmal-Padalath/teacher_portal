<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;

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

Route::get('/', function () {
    return view('login');
});
Route::post('/check-log-in', [TeacherController::class, 'checkLogin']);
Route::get('/teacher-dashboard', [TeacherController::class, 'dashboard']);
Route::post('/add-student', [TeacherController::class, 'addStudent']);
Route::post('/edit-student', [TeacherController::class, 'addStudent']);
Route::post('/delete-student', [TeacherController::class, 'deleteStudent']);
Route::get('/log-out', [TeacherController::class, 'logout']);
