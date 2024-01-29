<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model {
    use HasFactory;

    protected $fillable = [
        'color',
        'size',
        'qty',
        'price',
        'product_id',
        'user_id',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
