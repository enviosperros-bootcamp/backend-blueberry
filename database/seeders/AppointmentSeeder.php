<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use DateTime;
use DateInterval;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::where('role', 'patient')->get();
        $services = Service::all();

        if ($patients->isEmpty() || $services->isEmpty()) {
            $this->command->warn('No hay pacientes o servicios suficientes. Asegúrate de tenerlos creados.');
            return;
        }

        foreach ($patients as $patient) {
            Appointment::factory()->count(rand(1, 3))->create([
                'patient_id' => $patient->id,
                'service_id' => $services->random()->id,
            ]);
        }
    }
}
