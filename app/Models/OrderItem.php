<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'price_sale',
        'product_info'
    ];

    protected $casts = [
        'product_info' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Accessor to get the archived product data
    public function getArchivedProductAttribute()
    {
        return $this->product_info['product'] ?? null;
    }

    // Accessor to get the archived variant data
    public function getArchivedVariantAttribute()
    {
        return $this->product_info['variant'] ?? null;
    }

    // Helper method to get the product name (from archive if product is deleted)
    public function getProductNameAttribute()
    {
        if ($this->product) {
            return $this->product->name;
        }
        return $this->archived_product['name'] ?? 'Product Not Found';
    }

    // Helper method to get the variant name (from archive if variant is deleted)
    public function getVariantNameAttribute()
    {
        if ($this->productVariant) {
            return $this->productVariant->name;
        }
        return $this->archived_variant['name'] ?? null;
    }

    // Helper method to get the price (from archive if product/variant is deleted)
    public function getArchivedPriceAttribute()
    {
        if ($this->productVariant) {
            return $this->productVariant->price_sale ?? $this->productVariant->price;
        }
        if ($this->product) {
            return $this->product->price_sale ?? $this->product->price;
        }
        return $this->archived_variant['price_sale'] ??
            $this->archived_variant['price'] ??
            $this->archived_product['price_sale'] ??
            $this->archived_product['price'] ??
            0;
    }
}
