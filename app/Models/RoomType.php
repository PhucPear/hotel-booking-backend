<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomType extends Model
{
    use HasFactory;

    protected $table = 'room_types';

    protected $fillable = [
        'name',
        'price',
        'capacity',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relationship: Room type has many rooms.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_type_amenity');
    }

    public function images()
    {
        return $this->hasMany(RoomTypeImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(RoomTypeImage::class)->where('is_primary', true);
    }
}
