<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'driver_id',
        'booking_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'created_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'created_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}