<?php

use App\Http\Controllers\CitaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::get('users', 'index');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ServicioController::class)->group(function () {
    Route::post('store','store');
    Route::get('servicios','index');
});

Route::controller(CitaController::class)->group(function () {
    Route::post('citas/store','store');
    Route::get('citas/index','index');
<<<<<<< HEAD
    route::post('citas/aceptar/{id}','aceptarCita');
=======
    Route::post('citas/aceptar/{id}','aceptCita');
>>>>>>> 11398f7fd8e3565ee877f38262843c3255728f70
});

Route::controller(CalendarController::class)->group(function () {
    Route::get('calendar','token');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users','index');
});
