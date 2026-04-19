<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permissionName)
    {
        if ($this->role?->name === 'admin') {
            return true;
        }

        return $this->role && $this->role->permissions->contains('name', $permissionName);
    }
}
