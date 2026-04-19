<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $table = 'services';

    protected $fillable = [
        'name',
        'price',
        'is_free'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_services');
    }
}
