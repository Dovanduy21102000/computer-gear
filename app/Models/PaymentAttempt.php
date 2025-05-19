<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method',
        'order_code',
        'amount',
        'status',
        'selected_items',
        'shipping_info',
        'coupon_info',
        'expires_at'
    ];

    protected $casts = [
        'selected_items' => 'array',
        'shipping_info' => 'array',
        'coupon_info' => 'array',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'code', 'order_code');
    }
}
