<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\ErrorCode;
use App\Events\BookingCreated;
use App\Exceptions\BaseApiException;
use App\Models\BookingDetail;
use App\Models\Room;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected $bookingRepo;
    protected $cacheService;

    public function __construct(
        BookingRepositoryInterface $bookingRepo,
        CacheService $cacheService
    ) {
        $this->bookingRepo = $bookingRepo;
        $this->cacheService = $cacheService;
    }

    public function createBooking($data, $user)
    {
        return $this->cacheService
            ->lock("booking_user_{$user}", 10)
            ->block(5, function () use ($data) {

                DB::beginTransaction();
                try {
                    $total = 0;

                    $booking = $this->bookingRepo->create([
                        'user_id' => 1,
                        'status' => BookingStatus::PENDING,
                        'total_price' => 0
                    ]);

                    foreach ($data['rooms'] as $room) {

                        if (!$this->isRoomAvailable(
                            $room['room_id'],
                            $room['check_in'],
                            $room['check_out']
                        )) {
                            throw new BaseApiException(ErrorCode::BOOKING_ROOM_NOT_AVAILABLE);
                        }

                        $days = Carbon::parse($room['check_in'])
                            ->diffInDays(Carbon::parse($room['check_out']));

                        $roomModel = Room::with('type')->findOrFail($room['room_id']);
                        $price = $roomModel->type->price * $days;

                        $total += $price;

                        BookingDetail::create([
                            'booking_id' => $booking->id,
                            'room_id' => $room['room_id'],
                            'check_in_date' => $room['check_in'],
                            'check_out_date' => $room['check_out'],
                            'price' => $price
                        ]);

                        // clear cache
                        $this->cacheService->forget(
                            "room_{$room['room_id']}_{$room['check_in']}_{$room['check_out']}"
                        );
                    }

                    $booking = $this->bookingRepo->update($booking->id, [
                        'total_price' => $total
                    ]);

                    DB::commit();

                    // event send mail to user after booking created
                    //event(new BookingCreated($booking));

                    return $booking;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            });
    }

    // check room is available or not
    public function isRoomAvailable($roomId, $checkIn, $checkOut)
    {
        $key = "room_{$roomId}_{$checkIn}_{$checkOut}";

        return $this->cacheService->remember($key, 60, function () use ($roomId, $checkIn, $checkOut) {
            return !BookingDetail::where('room_id', $roomId)
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                        });
                })
                ->exists();
        });
    }

    public function getBooking($id)
    {
        return $this->bookingRepo->find($id);
    }

    public function getBookings(array $filters = [])
    {
        return $this->bookingRepo->getList($filters);
    }

    public function getBookingByUser($userId)
    {
        return $this->bookingRepo->getOne([
            'user_id' => $userId
        ]);
    }
}
