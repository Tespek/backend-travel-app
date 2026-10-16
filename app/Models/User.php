<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'operator_id',
        'status',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function driverAssignments()
    {
        return $this->hasMany(Booking::class, 'driver_id');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeDrivers($query)
    {
        return $query->where('role', 'driver');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}