<?php
namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';

    public function status(): int
    {
        return match($this) {
            self::PENDING => 0,
            self::CONFIRMED => 1,
            self::CANCELLED => 2,
        };
    }

    public function message(): string
    {
        return match($this) {
            self::PENDING => __('messages.booking.pending'),
            self::CONFIRMED => __('messages.booking.confirmed'),
            self::CANCELLED => __('messages.booking.cancelled'),
        };
    }
}