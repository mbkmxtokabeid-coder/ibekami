<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name_id', 'name_en', 'image_url'];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_type', 'id');
    }

    /**
     * Nama jenis produk sesuai locale aktif (id/en), dengan fallback.
     */
    protected function name(): Attribute
    {
        return Attribute::get(function () {
            if (app()->getLocale() === 'en') {
                return $this->name_en ?: $this->name_id;
            }

            return $this->name_id ?: $this->name_en;
        });
    }
}
