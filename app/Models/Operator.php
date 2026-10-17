<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone',
        'email',
        'owner_name',
        'document_ktp',
        'document_siup',
        'verification_status',
        'rejection_reason',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(User::class)->where('role', 'driver');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function pickupPoints()
    {
        return $this->hasMany(OperatorPickupPoint::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }
}