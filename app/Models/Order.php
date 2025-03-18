<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'shipping_user_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'province_id',
        'district_id',
        'specific_address',
        'coupon_code',
        'coupon_discount',
        'total_price',
        'final_price',
        'payment_status',
        'payment_method',
        'status',
        'notes',
    ];
    public $attributes = [
        'payment_status' => 1
    ];

    // Liên kết với bảng users (Người dùng)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
