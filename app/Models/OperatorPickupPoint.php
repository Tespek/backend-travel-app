<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorPickupPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'city_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'pickup_point_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}