<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Breakfast',
                'Airport Pickup',
                'Spa',
                'Laundry',
                'Gym Access',
                'Room Cleaning',
                'Dinner',
                'Business Meeting',
                'Meeting Room',
                'Parking',
            ]),
            'price' => $this->faker->numberBetween(0, 1200),
            'is_free' => $this->faker->boolean(30),
        ];
    }
}
