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

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function images()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id');
    }

    public function primaryImage()
    {
        return $this->belongsTo(RoomTypeImage::class, 'primary_image_id');
    }
}
