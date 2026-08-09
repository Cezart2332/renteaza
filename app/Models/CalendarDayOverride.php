<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDayOverride extends Model
{
    protected $table = 'calendar_day_overrides';

    protected $fillable = [
        'vehicle_id',
        'date',
        'custom_price',
        'is_blocked',
    ];

    protected $casts = [
        'date' => 'date',
        'custom_price' => 'decimal:2',
        'is_blocked' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
