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
