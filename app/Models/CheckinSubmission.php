<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckinSubmission extends Model
{
    protected $table = 'inspection_submissions';

    protected $fillable = [
        'booking_id',
        'submitted_by',
        'status',
        'notes',
        'type', // 'checkin' | 'checkout'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CheckinPhoto::class, 'submission_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
