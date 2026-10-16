<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'status',
        'note',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}