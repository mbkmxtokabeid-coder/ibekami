<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    // Primary key adalah UUID product_id
    protected $primaryKey = 'product_id';
    public    $incrementing = false;
    protected $keyType     = 'string';

    protected $fillable = [
        'product_id', 'name', 'product_type', 'category_type',
        'price', 'discount', 'image_url', 'detail', 'description',
        'click_count', 'order_click_count', 'status', 'activated_at',
    ];

    protected $casts = [
        'image_url'    => 'array',
        'detail'       => 'array',
        'activated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->product_id)) {
                $product->product_id = (string) Str::uuid();
            }
        });
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->status === 'Aktif';
    }

    /**
     * Resolve URL gambar produk dari filename yang tersimpan di DB.
     *
     * Ada dua format path historis:
     *  - Lama (seeder/import) : disimpan di  storage/app/public/gambar_produk/
     *  - Baru (upload admin)  : disimpan di  storage/app/public/products/
     *
     * Method ini mencoba kedua lokasi secara berurutan agar backward-compatible.
     */
    public static function resolveImageUrl(string $filename): string
    {
        // Sudah berupa URL lengkap — kembalikan apa adanya
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        // Coba folder baru dulu (upload dari admin panel)
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('products/' . $filename)) {
            return asset('storage/products/' . rawurlencode($filename));
        }

        // Fallback ke folder lama (data seeder / import)
        return asset('storage/gambar_produk/' . rawurlencode($filename));
    }

    /**
     * Ambil URL gambar pertama dari produk ini.
     */
    public function getFirstImageUrl(): string
    {
        $images = $this->image_url;

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        if (!empty($images) && is_array($images)) {
            return self::resolveImageUrl($images[0]);
        }

        return 'https://via.placeholder.com/400x300?text=' . urlencode($this->name ?? 'Product');
    }

    /**
     * Ambil semua URL gambar dari produk ini.
     *
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
