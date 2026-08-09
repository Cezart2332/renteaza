<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'website',
        'logo',
        'gallery_images',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'gallery_images' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function rentalTypePivots()
    {
        return $this->hasMany(\App\Models\RentalTypeVehicle::class, 'company_id');
    }

    public function vehiclesByRental()
    {
        return $this->belongsToMany(\App\Models\Vehicle::class, 'rental_type_vehicle', 'company_id', 'vehicle_id')
            ->withPivot('rental_type_id')
            ->withTimestamps();
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
