<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            $name = "Sản phẩm mẫu $i";
            DB::table('products')->insert([
                'category_id' => rand(1, 5), // giả sử bạn có 5 danh mục
                'brand_id' => rand(3, 5),    // giả sử bạn có 5 thương hiệu
                'sku' => 'SPM-' . Str::random(8),
                'name' => $name,
                'slug' => Str::slug($name),
                'thumbnail' => 'uploads/products/sample' . rand(1, 5) . '.jpg',
                'short_description' => 'Mô tả ngắn cho sản phẩm mẫu ' . $i,
                'description' => 'Chi tiết sản phẩm mẫu số ' . $i . '. Đây là một đoạn mô tả chi tiết.',
                'price' => rand(100, 2000) * 1000,
                'price_sale' => rand(0, 1) ? rand(100, 1500) * 1000 : null,
                'quantity' => rand(10, 100),
                'quantity_sold' => rand(0, 50),
                'status' => 1,
                'views' => rand(0, 1000),
                'is_variant' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
