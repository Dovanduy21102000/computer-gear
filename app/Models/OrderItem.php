<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')->withDefault();
    }

    public function getStoredProductInfo()
    {
        return $this->product_info ?? [];
    }

    public function getProductName()
    {
        if ($this->product && $this->product->exists) {
            return $this->product->name;
        }

        $storedInfo = $this->getStoredProductInfo();
        return $storedInfo['product']['name'] ?? 'Deleted Product';
    }

    public function getProductVariantInfo()
    {
        if ($this->productVariant && $this->productVariant->exists) {
            return $this->productVariant;
        }

        $storedInfo = $this->getStoredProductInfo();
        return $storedInfo['variant'] ?? null;
    }

    public function archiveProductImage()
    {
        if (!$this->product_info) {
            return;
        }

        $storedInfo = $this->getStoredProductInfo();
        $product = $storedInfo['product'] ?? null;
        $variant = $storedInfo['variant'] ?? null;

        if ($product && isset($product['thumbnail'])) {
            $originalPath = $product['thumbnail'];
            if (Storage::disk('public')->exists($originalPath)) {
                $newPath = 'archives/products/' . basename($originalPath);
                Storage::disk('public')->copy($originalPath, $newPath);
                $product['thumbnail'] = $newPath;
            }
        }

        if ($variant && isset($variant['image'])) {
            $originalPath = $variant['image'];
            if (Storage::disk('public')->exists($originalPath)) {
                $newPath = 'archives/variants/' . basename($originalPath);
                Storage::disk('public')->copy($originalPath, $newPath);
                $variant['image'] = $newPath;
            }
        }

        $this->product_info = [
            'product' => $product,
            'variant' => $variant
        ];
        $this->save();
    }

    public function getProductImage()
    {
        if ($this->product && $this->product->exists) {
            return $this->product->thumbnail;
        }

        $storedInfo = $this->getStoredProductInfo();
        return $storedInfo['product']['thumbnail'] ?? null;
    }

    public function getProductVariantImage()
    {
        if ($this->productVariant && $this->productVariant->exists) {
            return $this->productVariant->image;
        }

        $storedInfo = $this->getStoredProductInfo();
        return $storedInfo['variant']['image'] ?? null;
    }

    public function getDisplayImage()
    {
        return $this->getProductVariantImage() ?? $this->getProductImage();
    }
}
