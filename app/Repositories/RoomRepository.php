<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
  private function baseQuery()
  {
    return Room::with([
      'type',
      'type.images',
      'amenities',
      'services'
    ]);
  }

  private function applyFilters($query, array $filters)
  {
    // filter by room type
    if (!empty($filters['room_type_id'])) {
      $query->where('room_type_id', $filters['room_type_id']);
    }

    // filter by amenities
    if (!empty($filters['amenity_id'])) {
      $query->whereHas('amenities', function ($q) use ($filters) {
        $q->where('amenity_id', $filters['amenity_id']);
      });
    }

    // filter by services
    if (!empty($filters['service_id'])) {
      $query->whereHas('services', function ($q) use ($filters) {
        $q->where('service_id', $filters['service_id']);
      });
    }

    // filter by capacity
    if (!empty($filters['capacity'])) {
      $query->whereHas('type', function ($q) use ($filters) {
        $q->where('capacity', '>=', $filters['capacity']);
      });
    }

    // search keyword
    if (!empty($filters['keyword'])) {
      $query->where('name', 'like', '%' . $filters['keyword'] . '%');
    }

    return $query;
  }

  public function getList(array $filters = [])
  {
    $query = $this->baseQuery();

    $this->applyFilters($query, $filters);

    // dynamic paginate
    $perPage = $filters['per_page'] ?? 10;

    return $query->paginate($perPage);
  }

  public function getOne(array $filters)
  {
    $query = $this->baseQuery();

    $this->applyFilters($query, $filters);

    return $query->first();
  }
}
