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
        Schema::create('doctor_infos', function (Blueprint $table) {
            $table->id(); // id del doctor_info
            $table->foreignId('user_id')->constrained('users'); // FK a la tabla 'users' (usuario)
            $table->string('professional_license'); // Licencia profesional del doctor
            $table->foreignId('specialty_id')->constrained('specialties'); // FK a la tabla 'specialties'
            $table->foreignId('service_id')->constrained('services'); // FK a la tabla 'services'
            $table->foreignId('feedback_id')->nullable()->constrained('feedback'); // FK a la tabla 'feedback'
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_infos');
    }
};
