<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends BaseApiController
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $bookings = $this->bookingService->getBookings($request->all());

        return $this->success(BookingResource::collection($bookings), __('messages.booking.list_success'));
    }

    public function store(BookingRequest $request)
    {
        $userID = $this->user()->getAuthIdentifier();
        $data = $request->validated();

        $booking = $this->bookingService->createBooking($data, $userID);

        return $this->success(BookingResource::make($booking), __('messages.booking.create_success'));
    }

    public function show($id)
    {
        $booking = $this->bookingService->getBooking($id);

        return $this->success(BookingResource::make($booking));
    }
}
