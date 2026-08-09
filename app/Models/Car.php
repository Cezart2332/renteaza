<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'brand',
        'model',
        'type',
        'year',
        'fuel_type',
        'autonomy_km',
        'transmission',
        'price_per_day',
        'cover_image',
        'gallery_images',
        'location',
        'license_plate',
        'insurance_valid_until',
        'description',
        'availability_calendar',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'availability_calendar' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rentalAvailabilities()
    {
        return $this->hasMany(RentalAvailability::class);
    }
}
