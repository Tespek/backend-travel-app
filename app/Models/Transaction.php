<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'operator_id',
        'gross_amount',
        'platform_fee',
        'net_amount',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}