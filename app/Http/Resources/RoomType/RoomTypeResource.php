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
            'primary_image' => [
                'id' => $this->primary_image_id,
                'image_url' => $this->primaryImage->image_url,
            ],
        ];
    }
}
