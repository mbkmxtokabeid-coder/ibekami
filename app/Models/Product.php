<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    // Primary key adalah UUID product_id
    protected $primaryKey = 'product_id';
    public    $incrementing = false;
    protected $keyType     = 'string';

    protected $fillable = [
        'product_id', 'name_id', 'name_en', 'product_type', 'category_type',
        'price', 'discount', 'image_url', 'detail_id', 'detail_en',
        'description_id', 'description_en',
        'click_count', 'order_click_count', 'status', 'activated_at',
    ];

    protected $casts = [
        'image_url'      => 'array',
        'detail_id'      => 'array',
        'detail_en'      => 'array',
        'activated_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->product_id)) {
                $product->product_id = (string) Str::uuid();
            }
        });
    }

    protected function name(): Attribute
    {
        return Attribute::get(function () {
            if (app()->getLocale() === 'en') {
                return $this->name_en ?: $this->name_id;
            }

            return $this->name_id ?: $this->name_en;
        });
    }

    protected function description(): Attribute
    {
        return Attribute::get(function () {
            if (app()->getLocale() === 'en') {
                return $this->description_en ?: $this->description_id;
            }

            return $this->description_id ?: $this->description_en;
        });
    }

    protected function detail(): Attribute
    {
        return Attribute::get(function () {
            if (app()->getLocale() === 'en') {
                return $this->detail_en ?: $this->detail_id ?: [];
            }

            return $this->detail_id ?: $this->detail_en ?: [];
        });
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->status === 'Aktif';
    }

    /**
     * Slug URL stabil berdasarkan nama Indonesia.
     */
    public function getSlug(): string
    {
        return Str::slug($this->name_id ?: $this->name_en ?: 'product');
    }

    /**
     * Resolve URL gambar produk dari filename yang tersimpan di DB.
     */
    public static function resolveImageUrl(string $filename): string
    {
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('products/' . $filename)) {
            return asset('storage/products/' . rawurlencode($filename));
        }

        return asset('storage/gambar_produk/' . rawurlencode($filename));
    }

    public function getFirstImageUrl(): string
    {
        $images = $this->image_url;

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        if (!empty($images) && is_array($images)) {
            return self::resolveImageUrl($images[0]);
        }

        return 'https://via.placeholder.com/400x300?text=' . urlencode($this->name_id ?? $this->name_en ?? 'Product');
    }

    /**
     * @return string[]
     */
    public function getAllImageUrls(): array
    {
        $images = $this->image_url;

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        if (!empty($images) && is_array($images)) {
            return array_map(fn ($img) => self::resolveImageUrl($img), $images);
        }

        return [$this->getFirstImageUrl()];
    }

    // ── Relations ─────────────────────────────────────────────────
    public function type()
    {
        return $this->belongsTo(Type::class, 'product_type', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_type', 'id');
    }
}
