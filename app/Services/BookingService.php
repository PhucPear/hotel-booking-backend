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
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected CacheService $cacheService
    ) {}

    public function createBooking($data, $userID)
    {
        return $this->cacheService
            ->lock("booking_user_{$userID}", 10)
            ->block(5, function () use ($data, $userID) {

                DB::beginTransaction();

                try {
                    $total = 0;

                    $booking = $this->bookingRepo->create([
                        'user_id' => $userID,
                        'status' => BookingStatus::PENDING,
                        'total_price' => 0
                    ]);

                    foreach ($data['rooms'] as $room) {

                        // check + lock DB
                        $isAvailable = $this->checkAndLockRoom(
                            $room['room_id'],
                            $room['check_in'],
                            $room['check_out']
                        );

                        if (!$isAvailable) {
                            DB::rollBack();

                            throw new BaseApiException(ErrorCode::BOOKING_ROOM_NOT_AVAILABLE);
                        }

                        $days = Carbon::parse($room['check_in'])
                            ->diffInDays(Carbon::parse($room['check_out']));

                        $roomModel = Room::with('type')
                            ->lockForUpdate()
                            ->findOrFail($room['room_id']);

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
                    event(new BookingCreated($booking));

                    return $booking;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            });
    }

    // check room is available or not
    private function buildConflictQuery($roomId, $checkIn, $checkOut)
    {
        return BookingDetail::where('room_id', $roomId)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            });
    }

    // check room is available or not (lock for update)
    public function checkAndLockRoom($roomId, $checkIn, $checkOut)
    {
        return !$this->buildConflictQuery($roomId, $checkIn, $checkOut)
            ->lockForUpdate()
            ->exists();
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
