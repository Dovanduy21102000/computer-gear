<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean', // Đảm bảo status luôn là boolean
    ];

    protected $attributes = [
        'status' => true, // Đặt mặc định là true
    ];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
