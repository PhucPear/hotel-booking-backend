<?php

namespace App\Services;

use App\Enums\ErrorCode;
use App\Exceptions\BaseApiException;
use App\Repositories\Eloquent\RoomRepository;

class RoomSystemService
{
  public function __construct(
    protected RoomRepository $roomRepository
  ) {}

  public function getList(array $filters = [])
  {
    return $this->roomRepository->getList($filters);
  }

  public function getOne(array $filters)
  {
    $room = $this->roomRepository->getOne($filters);

    if (!$room) {
      throw new BaseApiException(ErrorCode::NOT_FOUND);
    }

    return $room;
  }
}
