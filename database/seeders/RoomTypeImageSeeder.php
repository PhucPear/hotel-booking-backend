<?php

namespace Database\Seeders;

use App\Models\RoomType;
use App\Models\RoomTypeImage;
use Illuminate\Database\Seeder;

class RoomTypeImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();

        foreach ($roomTypes as $roomType) {

            // nếu đã có ảnh thì skip
            if ($roomType->images()->exists()) {
                continue;
            }

            $images = RoomTypeImage::factory()
                ->count(rand(3, 5))
                ->create([
                    'room_type_id' => $roomType->id
                ]);

            // chọn một ảnh chính
            $primary = $images->random();

            $roomType->update([
                'primary_image_id' => $primary->id
            ]);
        }
    }
}
