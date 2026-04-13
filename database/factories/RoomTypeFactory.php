<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->randomElement([
                'Standard',
                'Deluxe',
                'Suite',
                'VIP'
            ]),

            'price' => match ($name) {
                'Standard' => 120000,
                'Deluxe'   => 200000,
                'Suite'    => 350000,
                'VIP'      => 500000,
            },

            'capacity' => match ($name) {
                'Standard' => 2,
                'Deluxe'   => 4,
                'Suite'    => 6,
                'VIP'      => 8,
            },

            'description' => fake()->sentence(12),
        ];
    }
}
