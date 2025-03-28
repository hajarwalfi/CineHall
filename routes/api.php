<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\SiegeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SeanceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::apiResource('films', FilmController::class);
Route::apiResource('seances', SeanceController::class);
Route::apiResource('salles', SalleController::class);
Route::apiResource('sieges', SiegeController::class);
Route::post('/reservations', [ReservationController::class, 'reserver']);

Route::post('/reserver-siege', [SiegeController::class, 'reserver']);
Route::get('reservations/expire', [ReservationController::class, 'checkAndExpireReservations']);


Route::get('seances/{seance}/sieges-disponibles', [SiegeController::class, 'getSiegesDisponibles']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::delete('user/{id}', [UserController::class, 'delete']);
Route::get('/seances/film/{filmId}', [SeanceController::class, 'getByFilm']);
