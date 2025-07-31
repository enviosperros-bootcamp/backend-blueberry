<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_id' => User::factory(), // Se puede ajustar según tu seeder
            'service_id' => Service::factory(),
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'motive' => $this->faker->sentence,
        ];
    }
}
