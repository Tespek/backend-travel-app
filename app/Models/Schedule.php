<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'departure_date',
        'departure_time',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'string',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeFuture($query)
    {
        return $query->where('departure_date', '>=', now()->toDateString());
    }
}