<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    protected $fillable = ['category', 'image_url'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage:partners');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage:partners');
        });
    }

    const CATEGORIES = ['BUMN', 'Organization'];
}
