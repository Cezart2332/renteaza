<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = ['booking_id', 'status', 'document_path', 'created_by'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function signers(): HasMany
    {
        return $this->hasMany(ContractSigner::class);
    }
}
