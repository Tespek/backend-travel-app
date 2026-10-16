<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'name',
        'is_active',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function pickupPoints()
    {
        return $this->hasMany(OperatorPickupPoint::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}