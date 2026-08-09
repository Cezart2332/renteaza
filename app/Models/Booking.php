<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'client_id',
        'vehicle_id',
        'owner_id',
        'start_date',
        'end_date',
        'price_per_day',
        'total_price',
        'security_deposit',
        'status',
        'pickup_location_id',
        'rental_type_id',
        'contract_s3_key',
        'owner_signed_at',
        'client_signed_at',
        'stripe_payment_intent',
        'stripe_transfer_id',
        'platform_fee_amount',
        'payout_status',
        'payout_provider',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'owner_signed_at' => 'datetime',
        'client_signed_at' => 'datetime',
    ];

    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function rentalType()
    {
        return $this->belongsTo(RentalType::class, 'rental_type_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'booking_id');
    }
}
