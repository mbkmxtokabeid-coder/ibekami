<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['media_url', 'media_type', 'thumbnail_url'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage:hero_banner');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage:hero_banner');
        });
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }
}
