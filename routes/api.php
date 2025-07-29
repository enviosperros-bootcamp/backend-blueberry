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

// Rutas para servicios
Route::post('/services', [ServiceController::class, 'store']);  // Crear servicio
Route::get('/services', [ServiceController::class, 'index']);  // Obtener servicios
Route::get('/services/{id}', [ServiceController::class, 'show']);  // Obtener servicio específico
Route::put('/services/{id}', [ServiceController::class, 'update']);  // Actualizar servicio
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);  // Eliminar servicio

// Rutas para citas
Route::post('/appointments', [AppointmentController::class, 'store']);  // Crear cita
Route::get('/appointments', [AppointmentController::class, 'index']);  // Obtener todas las citas
Route::get('/appointments/patient', [AppointmentController::class, 'getByPatient']);  // Obtener citas de un paciente
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);  // Eliminar cita

// Ruta para feedback 
Route::post('/feedbacks', [FeedbackController::class, 'store']);
Route::get('/feedbacks', [FeedbackController::class, 'index']);

// Ruta para la información del doctor 
Route::post('/doctor_infos', [DoctorInfoController::class, 'store']);
Route::get('/doctor_infos', [DoctorInfoController::class, 'index']);
Route::get('/doctor_infos/{id}/feedbacks', [DoctorInfoController::class, 'getFeedbacks']);

Route::middleware('auth:api')->group(function () {
    // Rutas protegidas
});
