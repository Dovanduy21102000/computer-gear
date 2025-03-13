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

        for ($i = 0; $i < 10; $i++) {
            $totalPrice = $faker->randomFloat(2, 100, 1000); // Tổng giá tiền
            $couponDiscount = $faker->randomFloat(2, 0, 50); // Giảm giá
            $finalPrice = $totalPrice - $couponDiscount; // Tính final_price trước khi insert
            DB::table('orders')->insert([
                'user_id' => rand(1, 10),
                'shipping_user_name' => $faker->name,
                'shipping_email' => $faker->email,
                'shipping_phone' => $faker->phoneNumber,
                'shipping_address' => $faker->streetAddress,
                'shipping_city' => $faker->city,
                'shipping_province' => $faker->state,
                'specific_address' => $faker->address,
                'coupon_code' => $faker->randomElement(['DISCOUNT10', 'SALE20', null]),
                'coupon_discount' => $couponDiscount,
                'total_price' => $totalPrice,
                'final_price' => $finalPrice, // Đã sửa, tính giá trị trực tiếp
                'payment_status' => rand(0, 1),
                'status' => $faker->randomElement(['pending', 'processing', 'delivered', 'completed', 'canceled']),
                'payment_method' => $faker->randomElement(['vn_pay', 'momo', 'cash']),
                'notes' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}