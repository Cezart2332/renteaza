<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CheckinPhoto extends Model
{
    protected $table = 'inspection_photos';

    protected $fillable = [
        'submission_id',
        'position',
        'path',
        'original_name',
        'mime',
        'size',
        'width',
        'height',
    ];

    protected $appends = ['url'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(CheckinSubmission::class);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->path ? Storage::disk('public')->url($this->path) : null;
    }
}
