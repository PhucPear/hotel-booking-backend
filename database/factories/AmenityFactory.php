<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Amenity>
 */
class AmenityFactory extends Factory
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
                'Wifi',
                'TV',
                'Air Conditioner',
                'Mini Bar',
                'Bathtub',
                'Balcony',
                'Hair Dryer',
                'Coffee Machine',
                'Safe Box',
                'Shower',
            ]),
            'icon' => 'icon-' . $this->faker->word()
        ];
    }
}
