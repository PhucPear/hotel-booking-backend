<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\RoomType\RoomTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,

            'type' => new RoomTypeResource($this->type),

            'images' => $this->type->images->map(function ($item) {
                return [
                    'id' => $item->id,
                    'image_url' => $item->image_url,
                ];
            }),

            'amenities' => $this->amenities->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon
                ];
            }),

            'services' => $this->services->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price
                ];
            }),
        ];
    }
}
