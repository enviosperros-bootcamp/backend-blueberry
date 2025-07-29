<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id(); // id de la cita
            $table->foreignId('patient_id')->constrained('users'); // FK a la tabla 'users' para el paciente
            $table->foreignId('service_id')->constrained('services'); // FK a la tabla 'services' para el servicio
            $table->dateTime('date'); // Fecha y hora de la cita
            $table->string('motive'); // Motivo de la cita
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
