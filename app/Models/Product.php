<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'tittle',
        'short_des',
        'price',
        'discount',
        'discount_price',
        'image',
        'stock',
        'star',
        'remark',
        'brand_id',
        'category_id',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
