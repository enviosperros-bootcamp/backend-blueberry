<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// RUTAS DE AUTENTICACIÓN JWT
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
});

Route::middleware('auth:api')->group(function () {
    // Rutas protegidas
});
