<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'plate_number',
        'brand',
        'capacity',
        'status',
        'description',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}