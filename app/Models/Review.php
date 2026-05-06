<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'review',
        'star',
        'review_date',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];
}
