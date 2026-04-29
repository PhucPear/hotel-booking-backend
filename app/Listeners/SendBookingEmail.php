<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\BookingMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        Log::info('SendBookingEmail triggered');

        Mail::to($event->booking->user->email)
            ->send(new BookingMail($event->booking));
    }
}
