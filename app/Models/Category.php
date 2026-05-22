<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['type_id', 'name_id', 'name_en'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forever('katalog_cache_version', time());
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forever('katalog_cache_version', time());
        });
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_type', 'id');
    }

    /**
     * Nama kategori sesuai locale aktif (id/en), dengan fallback.
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
