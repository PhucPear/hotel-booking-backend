<?php

namespace App\Services;

use App\Models\Room;

class RoomSystemService
{
  public function getList($request)
  {
    $query = Room::query()
      ->with([
        'type',
        'type.images',
        'amenities',
        'services'
      ]);

    return $query->paginate(5);
  }
}
