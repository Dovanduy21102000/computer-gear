<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'price_sale',
        'quantity',
        'image',
        'status',
    ];
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function attributes() {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values', 'product_variant_id', 'attribute_value_id');
    }
    public function attributeValues()
    {
        return $this->hasMany(ProductVariantAttributeValue::class, 'attribute_value_id');
    }
}
