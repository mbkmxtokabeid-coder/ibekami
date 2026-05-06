<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['type_id', 'name'];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_type', 'id');
    }
}
