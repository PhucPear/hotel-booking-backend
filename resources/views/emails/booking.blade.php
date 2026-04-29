<h2>Booking Confirmed</h2>

<p>Booking ID: {{ $booking->id }}</p>
<p>Total: {{ $booking->total_price }}</p>

@foreach($booking->details as $detail)
    <p>Room: {{ $detail->room_id }}</p>
    <p>Check-in: {{ $detail->check_in_date }}</p>
    <p>Check-out: {{ $detail->check_out_date }}</p>
@endforeach