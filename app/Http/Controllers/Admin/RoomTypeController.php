<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    // GET /api/room-types
    public function index()
    {
        $roomTypes = RoomType::with(['amenities', 'images'])->get();

        return response()->json([
            'status' => true,
            'message' => 'List room types',
            'data' => $roomTypes
        ]);
    }

    // GET /api/room-types/{id}
    public function show($id)
    {
        $roomType = RoomType::with(['amenities', 'images'])
            ->find($id);

        if (!$roomType) {
            return response()->json([
                'status' => false,
                'message' => 'Room type not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Room type detail',
            'data' => $roomType
        ]);
    }
}
