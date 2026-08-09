<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'pickup_location',
        'rent_type',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'available_from' => 'date',
        'available_until' => 'date',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
