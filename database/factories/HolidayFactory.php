<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomDigit(),
            'startingDate' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'endingDate' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'status' => fake()->name(),
        ];
    }
}
