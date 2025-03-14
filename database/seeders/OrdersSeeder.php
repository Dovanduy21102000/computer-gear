<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $paymentMethod = $faker->randomElement(['vn_pay', 'momo', 'cash']); // Chọn phương thức thanh toán
            $paymentStatus = in_array($paymentMethod, ['vn_pay', 'momo']) ? 1 : 0; // Nếu là vn_pay hoặc momo thì = 1, còn cash thì = 0
            $totalPrice = $faker->randomFloat(2, 100, 1000); // Tổng giá tiền
            $couponDiscount = $faker->randomFloat(2, 0, 50); // Giảm giá
            $finalPrice = $totalPrice - $couponDiscount; // Tính final_price trước khi insert

            DB::table('orders')->insert([
                'user_id' => rand(1, 10),
                'shipping_user_name' => $faker->name,
                'shipping_email' => $faker->email,
                'shipping_phone' => $faker->phoneNumber,
                'shipping_address' => $faker->streetAddress,
                'province_id' => rand(1, 10),
                'district_id' => rand(1, 30),
                'specific_address' => $faker->address,
                'coupon_code' => $faker->randomElement(['DISCOUNT10', 'SALE20', null]),
                'coupon_discount' => $couponDiscount,
                'total_price' => $totalPrice,
                'final_price' => $finalPrice, // Đã sửa, tính giá trị trực tiếp
                'payment_status' => $paymentStatus, // Được tính dựa vào payment_method
                'status' => $faker->randomElement(['pending', 'processing', 'delivered', 'completed', 'canceled']),
                'payment_method' => $paymentMethod, // Sử dụng biến đã tạo ở trên
                'notes' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
