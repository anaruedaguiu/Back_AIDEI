<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => fake()->randomDigit(),
            'startingDate' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'endingDate' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'startingTime' => fake()->time($format = 'H:i', $max = 'now'),
            'endingTime' => fake()->time($format = 'H:i', $max = 'now'),
            'description' => fake()->name(),
            'addDocument' => fake()->imageUrl(),
            'status' => fake()->name(),
        ];
    }
}
