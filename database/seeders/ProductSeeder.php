<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Khởi tạo Faker
        $faker = Faker::create();

        // Lấy danh sách category_id và brand_id từ database
        $categoryIds = Category::pluck('id')->toArray();
        $brandIds = Brand::pluck('id')->toArray();

        // Tạo 100 sản phẩm giả
        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'category_id' => $faker->randomElement($categoryIds),
                'brand_id' => $faker->randomElement($brandIds),
                'sku' => 'SKU-' . $faker->unique()->numberBetween(1000, 9999),
                'name' => $faker->words(3, true), // Tên sản phẩm gồm 3 từ
                'slug' => $faker->slug,
                'thumbnail' => $faker->imageUrl(400, 300, 'technics'), // Ảnh giả
                'short_description' => $faker->sentence,
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(100000, 10000000), // Giá từ 100,000 đến 10,000,000
                'price_sale' => $faker->optional()->numberBetween(50000, 5000000), // Giá khuyến mãi (có thể null)
                'quantity' => $faker->numberBetween(0, 100),
                'status' => $faker->boolean,
                'is_variant' => $faker->boolean,
            ]);
        }
    }
}
