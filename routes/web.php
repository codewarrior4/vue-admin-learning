<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientsController;
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
 
Route::middleware('auth')->group(function (){
    Route::get('/api/users',[UserController::class,'index']);
    Route::post('/api/users',[UserController::class,'store']);
    Route::put('/api/users/{user}',[UserController::class,'update']);
    Route::delete('/api/users/{user}',[UserController::class,'destroy']);
    Route::put('/api/users/{user}/role',[UserController::class,'updateRoles']); 
    Route::delete('/api/users',[UserController::class,'bulkDelete']);
    Route::get('/api/stats/users',[UserController::class,'userStats']);


    // appointments
    Route::get('/api/appointments',[AppointmentController::class,'index']);
    Route::get('/api/stats/appointments',[AppointmentController::class,'appointmentStats']);
    Route::get('/api/appointments-status',[AppointmentController::class,'getStatusWithCount']);
    Route::post('/api/appointments/create',[AppointmentController::class,'store']);
    Route::get('/api/appointments/{appointment}/edit',[AppointmentController::class,'edit']);
    Route::put('/api/appointments/{appointment}',[AppointmentController::class,'update']);
    Route::delete('/api/appointments/{appointment}',[AppointmentController::class,'destroy']);
    Route::get('/api/clients',[ClientsController::class,'index']);

});


Route::get('{view}',ApplicationController::class)->where('view','(.*)')->middleware('auth');
