<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'pickup_location_id');
    }
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_location')
            ->using(VehicleLocation::class)
            ->withTimestamps();
    }

}
