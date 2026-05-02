<?php

namespace App\Services;

use App\Enums\ErrorCode;
use App\Exceptions\BaseApiException;
use App\Repositories\Eloquent\RoomRepository;

class RoomSystemService
{
  public function __construct(
    protected RoomRepository $roomRepository,
    protected CacheService $cacheService
  ) {}

  public function getList(array $filters = [])
  {
    $key = 'rooms:' . md5(json_encode($filters));

    return $this->cacheService->remember($key, 60, function () use ($filters) {
      return $this->roomRepository->getList($filters);
    });
  }

  public function getOne(array $filters)
  {
    $key = 'rooms:' . md5(json_encode($filters));

    $room = $this->cacheService->remember($key, 60, function () use ($filters) {
      return $this->roomRepository->getOne($filters);
    });

    if (!$room) {
      throw new BaseApiException(ErrorCode::NOT_FOUND);
    }

    return $room;
  }
}
