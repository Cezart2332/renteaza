<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['faq_category_id', 'question', 'answer', 'is_active'];

    /**
     * Get the category that owns the FAQ.
     */
    public function category()
    {
        return $this->belongsTo(FaqCategory::class);
    }
}
