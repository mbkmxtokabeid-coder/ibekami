<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = ['name_id', 'name_en', 'image_url'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forever('katalog_cache_version', time());
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals');
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals_id');
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals_en');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forever('katalog_cache_version', time());
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals');
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals_id');
            \Illuminate\Support\Facades\Cache::forget('homepage:hot_deals_en');
        });
    }

    /**
     * Nama promo sesuai locale aktif (id/en), dengan fallback.
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
