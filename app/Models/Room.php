<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'room_number',
        'room_type_id',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'room_services');
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
