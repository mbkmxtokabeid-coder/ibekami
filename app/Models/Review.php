<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'review_id',
        'review_en',
        'star',
        'review_date',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];

    /**
     * Teks review sesuai locale aktif (id/en), dengan fallback.
     */
    protected function review(): Attribute
    {
        return Attribute::get(function () {
            if (app()->getLocale() === 'en') {
                return $this->review_en ?: $this->review_id;
            }

            return $this->review_id ?: $this->review_en;
        });
    }
}
