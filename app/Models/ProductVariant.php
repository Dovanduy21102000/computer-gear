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

    protected $casts = [
        'status' => 'boolean',
    ];

    // Quan hệ với model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    // Quan hệ với AttributeValue thông qua bảng trung gian
    public function attributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values', 'product_variant_id', 'attribute_value_id');
    }

    // Nếu bạn muốn lấy các giá trị thuộc tính (AttributeValue), dùng quan hệ thuộc tính
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values', 'product_variant_id', 'attribute_value_id');
    }
}
