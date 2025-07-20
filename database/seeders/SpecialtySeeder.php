<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'Cardiología',
            'Pediatría',
            'Dermatología',
            'Ginecología',
            'Neurología',
            'Odontología',
            'Oftalmología',
            'Ortopedia',
        ];

        foreach ($specialties as $name) {
            Specialty::create(['name' => $name]);
        }
    }
}

