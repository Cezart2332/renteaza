<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the FAQs for the category.
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }
}
