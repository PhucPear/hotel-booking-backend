<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Room\RoomResource;
use App\Services\RoomSystemService;
use Illuminate\Http\Request;

class RoomController extends BaseApiController
{
    protected $roomService;

    public function __construct(RoomSystemService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        $rooms = $this->roomService->getList($request->all());

        return $this->success(RoomResource::collection($rooms), __('messages.room.list_success'));
    }

    public function show($id)
    {
        $room = $this->roomService->getOne([
            'id' => $id
        ]);

        return $this->success(RoomResource::make($room));
    }
}
