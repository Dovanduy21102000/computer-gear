<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
        {
            // Chèn dữ liệu vào bảng categories
            DB::table('categories')->insert([
                [
                    'id' => 1,
                    'name' => 'Category 1',
                    'slug' => 'category-1',
                    'parent_id' => null, // không có parent
                    'is_active' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'deleted_at' => null, // chưa bị xóa
                ],
                [
                    'id' => 2,
                    'name' => 'Category 2',
                    'slug' => 'category-2',
                    'parent_id' => 1, // có parent_id là 1
                    'is_active' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'deleted_at' => null,
                ],
                [
                    'id' => 3,
                    'name' => 'Category 3',
                    'slug' => 'category-3',
                    'parent_id' => null, // không có parent
                    'is_active' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'deleted_at' => null,
                ],
                // Thêm các bản ghi khác nếu cần
            ]);
        }
    }

