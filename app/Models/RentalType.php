<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'rental_type_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'rental_type_vehicle', 'rental_type_id', 'vehicle_id')
            ->withTimestamps();
    }
}
