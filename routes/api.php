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
use App\Http\Controllers\DoctorController;


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

Route::prefix('appointments')->group(function () {
    Route::post('{appointment_id}/feedback', [FeedbackController::class, 'store']);  // Crear un feedback asociado a una cita
});

Route::prefix('doctores')->group(function () {
    Route::get('/{id}/feedbacks', [DoctorInfoController::class, 'showFeedbacks']); // Ver feedbacks de un doctor
});

Route::prefix('doctores')->group(function () {
    Route::get('/{id}', [DoctorInfoController::class, 'show']); // Ver info del doctor (GET)
    Route::put('/{id}', [DoctorInfoController::class, 'update']); // Actualizar info del doctor (PUT)
    Route::post('/{id}/especialidad', [DoctorInfoController::class, 'assignSpecialty']); // Asignar especialidad (POST)
    Route::get('/{id}/especialidad', [DoctorInfoController::class, 'showSpecialty']); // Ver especialidad (GET)
    Route::post('/{id}/usuario', [DoctorInfoController::class, 'associateUser']); // Asociar usuario (POST)
    Route::get('/{id}/usuario', [DoctorInfoController::class, 'showUser']); // Ver usuario (GET)
    Route::post('/{id}/servicios', [DoctorInfoController::class, 'addService']); // Agregar servicio (POST)
    Route::get('/{id}/servicios', [DoctorInfoController::class, 'showServices']); // Ver servicios (GET)
    Route::get('/{id}/feedbacks', [DoctorInfoController::class, 'showFeedbacks']); // Ver feedbacks (GET)
});




Route::middleware('auth:api')->group(function () {
    // Rutas protegidas
    Route::get('/doctors', [DoctorController::class, 'index']);
    // Estadisticas
    Route::get('/appointments/statistics/monthly', [AppointmentController::class, 'monthlyAppointmentsByDoctor']);
    Route::get('/appointments/statistics/weekly', [AppointmentController::class, 'weeklyAppointmentsByDoctor']);
    Route::get('/patients/statistics/monthly', [AppointmentController::class, 'newPatientsPerMonthForDoctor']);
    Route::get('/appointments/statistics/age-distribution', [AppointmentController::class, 'ageDistributionByDoctor']);
    Route::get('/appointments/statistics/service-distribution', [AppointmentController::class, 'serviceDistributionByDoctor']);
    // Citas FullCalendar
    Route::get('/appointments/by-doctor', [AppointmentController::class, 'appointmentsByDoctor']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);

    // Citas proximas por paciente
    Route::get('/appointments/upcoming-by-patient', [AppointmentController::class, 'upcomingByPatient']);


});
