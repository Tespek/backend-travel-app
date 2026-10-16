<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'schedule_id',
        'operator_id',
        'passenger_name',
        'passenger_phone',
        'passenger_email',
        'seat_number',
        'pickup_type',
        'pickup_point_id',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'destination_id',
        'destination_address',
        'total_price',
        'payment_status',
        'payment_method',
        'payment_gateway_ref',
        'paid_at',
        'pickup_status',
        'driver_id',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'pickup_latitude' => 'decimal:7',
        'pickup_longitude' => 'decimal:7',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function pickupPoint()
    {
        return $this->belongsTo(OperatorPickupPoint::class, 'pickup_point_id');
    }

    public function destination()
    {
        return $this->belongsTo(OperatorPickupPoint::class, 'destination_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(PickupStatusLog::class);
    }

    public function driverLocations()
    {
        return $this->hasMany(DriverLocation::class);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}