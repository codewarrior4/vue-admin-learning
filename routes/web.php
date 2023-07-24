<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

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

// users
 
Route::get('/api/users',[UserController::class,'index']);
Route::post('/api/users',[UserController::class,'store']);
Route::put('/api/users/{user}',[UserController::class,'update']);
Route::delete('/api/users/{user}',[UserController::class,'destroy']);
Route::put('/api/users/{user}/role',[UserController::class,'updateRoles']);
Route::get('/api/users/search',  [UserController::class,'userSearch']);
Route::delete('/api/users',[UserController::class,'bulkDelete']);


// appointments
Route::get('/api/appointments',[AppointmentController::class,'index']);


Route::get('{view}',ApplicationController::class)->where('view','(.*)');
