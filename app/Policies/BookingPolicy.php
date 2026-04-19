<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    // admin bypass
    public function before(User $user)
    {
        if ($user->role?->name === 'admin' || $user->role?->name === 'staff') {
            return true;
        }
    }

    public function view(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }

    public function update(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }

    public function delete(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }
}
