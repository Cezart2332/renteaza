<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

class VehicleLocation extends Pivot
{
    protected $table = 'vehicle_location';

    protected $fillable = [
        'vehicle_id',
        'location_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
