<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Coupon::create([
            'name' => 'Giảm 10%',
            'code' => 'GIAM10',
            'type' => 'percent',
            'price' => 10,
            'maximum_amount' => 50,
            'min_order_total' => 100,
            'max_uses' => 100,
            'used_count' => 0,
            'status' => 1,
            'expire_date' => now()->addDays(30),
        ]);
    }
}
