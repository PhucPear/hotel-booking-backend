<?php

namespace App\Http\Resources\RoomType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
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
            'name' => $this->name,
            'price' => number_format($this->price, 0, ',', '.') . ' VND',
            'capacity' => $this->capacity,
            'description' => $this->description,
            'amenities' => $this->amenities?->pluck('name')->toArray(),
            'images' => $this->images?->pluck('image_url')->toArray(),
        ];
    }
}
