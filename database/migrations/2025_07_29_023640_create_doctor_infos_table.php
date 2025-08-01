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
            $table->id();

            // FK a users (doctor)
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('professional_license');

            // Si tienes feedback relacionado aquí, podrías agregar:
            $table->unsignedBigInteger('feedback_id')->nullable();
            // Opcionalmente:
            // $table->foreign('feedback_id')->references('id')->on('feedbacks')->onDelete('set null');

            $table->timestamps();
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
