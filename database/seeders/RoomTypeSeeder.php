<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Standard Single' => ['price' => 100000, 'capacity' => 1],
            'Standard Double' => ['price' => 150000, 'capacity' => 2],
            'Superior' => ['price' => 180000, 'capacity' => 2],
            'Deluxe' => ['price' => 220000, 'capacity' => 2],
            'Deluxe Family' => ['price' => 300000, 'capacity' => 4],
            'Suite' => ['price' => 400000, 'capacity' => 4],
            'Executive Suite' => ['price' => 550000, 'capacity' => 4],
            'Junior Suite' => ['price' => 350000, 'capacity' => 3],
            'Presidential Suite' => ['price' => 1000000, 'capacity' => 6],
            'VIP' => ['price' => 700000, 'capacity' => 5],
        ];

        foreach ($types as $name => $data) {
            RoomType::create([
                'name' => $name,
                'price' => $data['price'],
                'capacity' => $data['capacity'],
                'description' => fake()->sentence(10),
            ]);
        }
    }
}
