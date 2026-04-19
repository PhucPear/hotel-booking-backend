<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => 'R' . $this->faker->unique()->numberBetween(100, 999),
            'room_type_id' => RoomType::inRandomOrder()->value('id') ?? 1,
            'status' => $this->faker->randomElement(['available', 'maintenance']),
        ];
    }
}
