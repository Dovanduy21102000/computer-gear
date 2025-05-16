<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'coupons';
  protected $fillable = [
    'name',
    'code',
    'type',
    'price',
    'maximum_amount',
    'min_order_total',
    'used_count',
    'status',
    'is_public',
    'expire_date',
    'quantity'
  ];

  protected $casts = [
    'expire_date' => 'datetime',
    'is_public' => 'boolean',
    'status' => 'boolean'
  ];

  public function checkAndUpdateStatus()
  {
    if ($this->quantity <= 0) {
      $this->status = false;
      $this->save();
    }
  }

  public function useCoupon()
  {
    $this->quantity--;
    $this->used_count++;
    if ($this->quantity <= 0) {
      $this->status = false;
    }
    $this->save();
  }
}
