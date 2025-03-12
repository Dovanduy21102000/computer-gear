<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name',
      'code',
      'type',
      'price',
      'maximum_amount',
      'min_order_total',
      'max_uses',
      'used_count',
      'status',
      'expire_date',
    ];
}
