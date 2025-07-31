<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            "Alergología",
            "Anestesiología",
            "Angiología",
            "Cardiología",
            "Cirugía General",
            "Cirugía Plástica y Reconstructiva",
            "Cirugía Pediátrica",
            "Dermatología",
            "Endocrinología",
            "Gastroenterología",
            "Geriatría",
            "Ginecología y Obstetricia",
            "Hematología",
            "Infectología",
            "Medicina del Deporte",
            "Medicina de Rehabilitación",
            "Medicina Crítica",
            "Medicina Interna",
            "Medicina Familiar",
            "Nefrología",
            "Neumología",
            "Neurología",
            "Nutriología Clínica",
            "Oftalmología",
            "Oncología Médica",
            "Ortopedia y Traumatología",
            "Otorrinolaringología",
            "Patología",
            "Pediatría",
            "Psiquiatría",
            "Radiología",
            "Reumatología",
            "Urología",
        ];

        foreach ($specialties as $name) {
            Specialty::create(['name' => $name]);
        }
    }
}
