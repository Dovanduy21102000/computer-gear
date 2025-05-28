<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    public function deleted(Product $product)
    {
        // Archive product info
        $archiveData = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'price_sale' => $product->price_sale,
            'quantity' => $product->quantity,
            'thumbnail' => $product->thumbnail,
            'gallery' => $product->gallery,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'deleted_at' => now(),
        ];

        // Save to archive file
        $filename = 'archives/products/product_' . $product->id . '_' . time() . '.json';
        Storage::disk('public')->put($filename, json_encode($archiveData, JSON_PRETTY_PRINT));
    }
}
