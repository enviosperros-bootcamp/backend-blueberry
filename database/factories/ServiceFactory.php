<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Service::class;

    public function definition(): array
    {
        $doctor = User::where('role', 'doctor')->inRandomOrder()->first()
            ?? User::factory()->create(['role' => 'doctor']);

        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 300, 1500),
            'doctor_id' => $doctor->id,
            'service_type' => $this->faker->randomElement(['consulta', 'cirugía', 'estudio']),
        ];
    }
}
