<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    
    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
