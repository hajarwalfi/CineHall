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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [UserController::class, 'show']);
    Route::put('profile', [UserController::class, 'update']);
    Route::delete('profile', [UserController::class, 'destroy']);
});


// Routes pour les salles
Route::get('/salles', [SalleController::class, 'index']);
Route::get('/salles/vip', [SalleController::class, 'vipSalles']);
Route::get('/salles/{id}', [SalleController::class, 'show']);
Route::post('/salles', [SalleController::class, 'store']);
Route::put('/salles/{id}', [SalleController::class, 'update']);
Route::delete('/salles/{id}', [SalleController::class, 'destroy']);

// Routes pour les sièges
Route::get('/sieges', [SiegeController::class, 'index']);
Route::get('/sieges/{id}', [SiegeController::class, 'show']);
Route::post('/sieges', [SiegeController::class, 'store']);
Route::post('/sieges/couple', [SiegeController::class, 'createCoupleSeats']);
Route::put('/sieges/{id}', [SiegeController::class, 'update']);
Route::delete('/sieges/{id}', [SiegeController::class, 'destroy']);
Route::get('/salles/{salleId}/sieges', [SiegeController::class, 'getBySalle']);

// Routes pour les réservations
Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::get('/reservations/code/{code}', [ReservationController::class, 'getByCode']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::put('/reservations/{id}', [ReservationController::class, 'update']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
Route::post('/reservations/{id}/payer', [ReservationController::class, 'payer']);
Route::get('/seances/{seanceId}/reservations', [ReservationController::class, 'getBySeance']);
Route::get('/seances/{seanceId}/sieges-disponibles', [ReservationController::class, 'getSiegesDisponibles']);
Route::get('/seances/{seanceId}/carte-sieges', [ReservationController::class, 'getCarteSieges']);
Route::post('/reservations/annuler-expirees', [ReservationController::class, 'annulerExpirees']);






Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

// Routes pour les films
Route::get('/films', [FilmController::class, 'index']);
Route::get('/films/{id}', [FilmController::class, 'show']);
Route::post('/films', [FilmController::class, 'store']);
Route::put('/films/{id}', [FilmController::class, 'update']);
Route::delete('/films/{id}', [FilmController::class, 'destroy']);

// Routes pour les séances
Route::get('/seances', [SeanceController::class, 'index']);
Route::get('/seances/{id}', [SeanceController::class, 'show']);
Route::post('/seances', [SeanceController::class, 'store']);
Route::put('/seances/{id}', [SeanceController::class, 'update']);
Route::delete('/seances/{id}', [SeanceController::class, 'destroy']);

// Route pour récupérer les séances d'un film
Route::get('/films/{filmId}/seances', [SeanceController::class, 'getByFilm']);


Route::apiResource('salles', SalleController::class);
Route::apiResource('sieges', SiegeController::class);
Route::post('/reservations', [ReservationController::class, 'reserver']);

Route::post('/reserver-siege', [SiegeController::class, 'reserver']);
Route::get('reservations/expire', [ReservationController::class, 'checkAndExpireReservations']);


Route::get('seances/{seance}/sieges-disponibles', [SiegeController::class, 'getSiegesDisponibles']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::delete('user/{id}', [UserController::class, 'delete']);
Route::get('/seances/film/{filmId}', [SeanceController::class, 'getByFilm']);
