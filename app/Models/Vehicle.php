<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CarType;

class Vehicle extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'owner_id',
        'vehicle_type_id',
        'brand',
        'model',
        'year',
        'description',
        'fuel_type_id',
        'transmission_id',
        'autonomy_km',
        'battery_capacity_kwh',
        'max_speed_kph',
        'seats',
        'doors',
        'cargo_volume_liters',
        'license_plate',
        'location',
        'cover_image',
        'gallery_images',
        'availability_calendar',
        'price_per_day',
        'is_verified',
        'status',
        'car_type',
        'company_id'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'availability_calendar' => 'array',
        'car_type' => CarType::class,
    ];

    /**
     * Vehiculele vizibile pentru vizitatori.
     *
     * Coloana `status` are default 'pending' la creare (OwnerCarController::store),
     * iar adminul o trece pe 'active' cand aproba masina. Pana atunci masina nu
     * are ce cauta in listarile publice.
     *
     * Trebuie folosit in TOATE locurile care expun vehicule catre public:
     * routes/web.php (landing), CarController::index, ::show si masinile similare.
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('status', DocumentStatus::ACTIVE->value);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function transmission()
    {
        return $this->belongsTo(Transmission::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'vehicle_location')
            ->using(VehicleLocation::class)
            ->withTimestamps();
    }

    public function documents()
    {
        // Return car-related documents only
        return $this->hasMany(Document::class)->whereIn('type', DocumentType::carDocumentTypes());
    }

    public function rentalTypes()
    {
        return $this->belongsToMany(RentalType::class, 'rental_type_vehicle', 'vehicle_id', 'rental_type_id')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'vehicle_id');
    }

    public function calendarDayOverrides()
    {
        return $this->hasMany(CalendarDayOverride::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
