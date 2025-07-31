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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id(); // id del feedback
            $table->foreignId('user_id')->constrained('users');  // El paciente (usuario) que hace la reseña
            $table->foreignId('doctor_id')->constrained('users'); // El doctor (usuario) que recibe el feedback
            $table->text('text'); // Comentarios del feedback
            $table->integer('score'); // Puntuación
            $table->foreignId('appointment_id')->constrained('appointments'); // Relacionado con la cita
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
