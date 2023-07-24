<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' =>$this->faker->sentence(),
            'start' =>$this->faker->dateTimeBetween('now', '+1 week'),
            'end' =>$this->faker->dateTimeBetween('+1 week', '+2 week'),
            'client_id' =>$this->faker->numberBetween(1, 10),
            'description' =>$this->faker->paragraph(),
            'status' =>$this->faker->numberBetween(0, 1),
        ];
    }
}
