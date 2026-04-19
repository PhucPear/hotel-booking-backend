<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Database\Seeder;

class RoomSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Amenities
        $amenities = Amenity::factory(10)->create();

        // Services
        $services = Service::factory(10)->create();

        // Rooms
        $rooms = Room::factory(20)->create();

        // Gán Amenities cho Room
        foreach ($rooms as $room) {
            $room->amenities()->attach(
                $amenities->random(rand(3, 6))->pluck('id')->toArray()
            );
        }

        // Gán Services cho Room
        foreach ($rooms as $room) {
            $room->services()->attach(
                $services->random(rand(2, 4))->pluck('id')->toArray()
            );
        }
    }
}
