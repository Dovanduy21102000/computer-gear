<?php

namespace App\Observers;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductVariantObserver
{
    public function deleted(ProductVariant $variant)
    {
        // Archive variant info
        $archiveData = [
            'id' => $variant->id,
            'product_id' => $variant->product_id,
            'name' => $variant->name,
            'sku' => $variant->sku,
            'price' => $variant->price,
            'price_sale' => $variant->price_sale,
            'quantity' => $variant->quantity,
            'created_at' => $variant->created_at,
            'updated_at' => $variant->updated_at,
            'deleted_at' => now(),
        ];

        // Save to archive file
        $filename = 'archives/variants/variant_' . $variant->id . '_' . time() . '.json';
        Storage::disk('public')->put($filename, json_encode($archiveData, JSON_PRETTY_PRINT));
    }
}
