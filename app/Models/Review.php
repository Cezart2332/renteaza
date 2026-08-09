<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'reviewer_id',
        'owner_id',
        'vehicle_id',
        'rating',
        'title',
        'description',
        'reviewed_at',
    ];
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];


    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
