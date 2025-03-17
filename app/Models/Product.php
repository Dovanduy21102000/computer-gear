<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'slug',
        'thumbnail',
        'short_description',
        'description',
        'price',
        'price_sale',
        'quantity',
        'status',
        'views',
        'is_variant',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với Brand (Một sản phẩm thuộc về một thương hiệu)
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
