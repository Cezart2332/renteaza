<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSigner extends Model
{
    protected $fillable = [
        'contract_id',
        'user_id',
        'role',
        'name_snapshot',
        'email_snapshot',
        'phone_snapshot',
        'has_signed',
        'signed_at',
        'signature_path'
    ];

    protected $casts = ['has_signed' => 'boolean', 'signed_at' => 'datetime'];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
