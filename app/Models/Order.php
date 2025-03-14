<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
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
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
