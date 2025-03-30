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
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            DB::table('products')->insert([
                'category_id' => rand(1, 4), // Giả định có 5 danh mục
                'brand_id' => rand(1, 3), // Giả định có 5 thương hiệu
                'sku' => 'SKU' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Sản phẩm ' . $i,
                'slug' => Str::slug('Sản phẩm ' . $i),
                'thumbnail' => 'https://via.placeholder.com/150?text=Product+' . $i,
                'short_description' => 'Mô tả ngắn gọn cho sản phẩm ' . $i,
                'description' => 'Mô tả chi tiết cho sản phẩm ' . $i,
                'price' => rand(100, 1000),
                'price_sale' => rand(80, 99) > 90 ? rand(50, 99) : null, // Tạo giá khuyến mãi ngẫu nhiên
                'quantity' => rand(0, 100),
                'quantity_sold' => rand(0, 50),
                'status' => rand(0, 1), // Ngẫu nhiên trạng thái
                'views' => rand(0, 1000),
                'is_variant' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
