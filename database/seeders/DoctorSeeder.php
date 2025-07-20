<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Location;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 10 doctores
        $doctors = User::factory()->count(10)->create([
            'role' => 'doctor',
        ]);

        $cities = ['CDMX', 'Guadalajara', 'Monterrey', 'Puebla', 'Mérida', 'Veracruz'];

        $specialties = Specialty::all();

        // Asignar de 1 a 3 especialidades aleatorias a cada doctor
        foreach ($doctors as $doctor) {
            foreach (range(1, rand(1, 2)) as $_) {
                Location::create([
                    'user_id' => $doctor->id,
                    'city' => fake()->randomElement($cities),
                    'address' => fake()->address(),
                ]);
            }
            $randomSpecialties = $specialties->random(rand(1, 3));
            $doctor->specialties()->attach($randomSpecialties->pluck('id')->toArray());
        }
    }
}
