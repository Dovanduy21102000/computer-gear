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
      'quantity',
      'used_count',
      'status',
      'expire_date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withTimestamps();
    }

    // public function checkAndUpdateStatus()
    // {
    //     if ($this->quantity <= 0) {
    //         $this->update(['status' => 0]); // Cập nhật trạng thái thành ngưng hoạt động
    //     }
    // }

    

    // public function useCoupon()
    // {
    //     if ($this->quantity > 0) {
    //         $this->decrement('quantity'); // Giảm số lượng còn lại
    //         $this->increment('used_count'); // Tăng số lần sử dụng

    //         // Kiểm tra nếu số lượng về 0, cập nhật trạng thái "ngưng hoạt động"
    //         if ($this->quantity <= 0) {
    //             $this->update(['status' => 0]);
    //         }
    //     }
    // }
}


