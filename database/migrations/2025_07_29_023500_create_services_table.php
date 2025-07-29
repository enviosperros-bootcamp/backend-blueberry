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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nombre del servicio
            $table->decimal('price', 8, 2);  // Precio del servicio
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');  // FK a la tabla 'users' (doctor)
            $table->string('service_type');  // Tipo de servicio (ej. consulta, cirugía, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
