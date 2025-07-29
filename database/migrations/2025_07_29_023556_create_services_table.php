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
            $table->id(); // id del servicio
            $table->string('name'); // Nombre del servicio
            $table->decimal('price', 8, 2); // Precio del servicio
            $table->foreignId('doctor_id')->constrained('users'); // Relación con la tabla 'users'
            $table->string('service_type'); // Tipo de servicio (por ejemplo, consulta, cirugía, etc.)
            $table->timestamps(); // created_at y updated_at
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
