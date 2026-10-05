<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['title', 'title_id', 'title_en', 'image_url'];

    /**
     * Judul mesin sesuai locale aktif (id/en), dengan fallback otomatis.
     */
    protected function title(): Attribute
    {
        return Attribute::get(function ($value) {
            if (app()->getLocale() === 'en') {
                return $this->title_en ?: ($value ?: $this->title_id);
            }

            return $this->title_id ?: ($value ?: $this->title_en);
        });
    }
}
