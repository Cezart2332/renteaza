<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RentalTypeVehicle extends Pivot{
     protected $table = 'rental_type_vehicle';

    protected $fillable = [
        'vehicle_id',
        'rental_type_id',
    ];
}
