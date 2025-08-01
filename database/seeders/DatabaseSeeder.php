<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        dump('Ejecutando DatabaseSeeder...');
        // User::factory(10)->create();

        /* DEFAULT CODE VALUES*/
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /* TESTING CODE VALUES*/
                $this->call([
            SpecialtySeeder::class,
            // DoctorSeeder::class,
            // ServiceSeeder::class,
            // AppointmentSeeder::class
        ]);
    }
}
