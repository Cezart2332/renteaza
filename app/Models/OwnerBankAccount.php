<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerBankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_holder_name',
        'iban',
        'bank_name',
        'currency',
        'status',
        'verified_at',
        'verified_by',
        'notes'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
