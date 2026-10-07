<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Type extends Model
{
    protected $fillable = ['name_id', 'name_en', 'image_url'];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('navbar:product_types_id');
            Cache::forget('navbar:product_types_en');
        });
        static::deleted(function () {
            Cache::forget('navbar:product_types_id');
            Cache::forget('navbar:product_types_en');
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'type_id', 'id');
    }

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

    /**
     * Slug resmi SEO untuk Type.
     */
    public function getSlug(): string
    {
        $customSlugs = [
            1  => 'suvenir',
            2  => 'plakat',
            8  => 'percetakan-digital',
            9  => 'akrilik',
            10 => 'simbol-k3-spesialis',
        ];

        return $customSlugs[$this->id] ?? \Illuminate\Support\Str::slug($this->name_id ?: $this->name_en ?: 'kategori');
    }

    /**
     * Cari Type berdasarkan slug atau aliasnya.
     */
    public static function findBySlug(string $slug): ?self
    {
        $aliases = [
            'suvenir'               => 1,
            'souvenir'              => 1,
            'souvenir-merchandise'  => 1,
            'plakat'                => 2,
            'plaque'                => 2,
            'plaque-plakat'         => 2,
            'percetakan-digital'    => 8,
            'digital-printing'      => 8,
            'akrilik'               => 9,
            'acrylic'               => 9,
            'simbol-k3-spesialis'   => 10,
            'specialist-k3-symbols' => 10,
        ];

        if (isset($aliases[$slug])) {
            $type = self::find($aliases[$slug]);
            if ($type) {
                return $type;
            }
        }

        return self::all()->first(function ($t) use ($slug) {
            return \Illuminate\Support\Str::slug($t->name_id ?: '') === $slug
                || \Illuminate\Support\Str::slug($t->name_en ?: '') === $slug
                || \Illuminate\Support\Str::slug($t->name) === $slug;
        });
    }
}
