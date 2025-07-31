<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DoctorInfoController;


// RUTAS DE AUTENTICACIÓN JWT
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
});

Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::put('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
});

Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index']); 
    Route::get('/by-patient', [AppointmentController::class, 'getByPatient']); 
    Route::post('/', [AppointmentController::class, 'store']); 
    Route::put('/{id}', [AppointmentController::class, 'update']); 
    Route::delete('/{id}', [AppointmentController::class, 'destroy']); 
});

// Ruta para feedback 
Route::prefix('feedbacks')->group(function () {
    Route::post('/', [FeedbackController::class, 'store']);
    Route::get('/', [FeedbackController::class, 'index']);
});
// Ruta para la información del doctor 
Route::post('/doctor_infos', [DoctorInfoController::class, 'store']);
Route::get('/doctor_infos', [DoctorInfoController::class, 'index']);
Route::get('/doctor_infos/{id}/feedbacks', [DoctorInfoController::class, 'getFeedbacks']);

Route::middleware('auth:api')->group(function () {
    // Rutas protegidas
});
