<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DoctorController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// endpoint busqueda por ciudad
//Route::get('/doctors', [DoctorController::class, 'index']);



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
