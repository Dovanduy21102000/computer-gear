<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('vi_VN');  // Sử dụng Faker với locale Việt Nam

        // Lấy tất cả tỉnh thành đã có từ bảng provinces
        $provinces = Province::all();

        foreach ($provinces as $province) {
            // Giả sử mỗi tỉnh có khoảng 5 quận huyện
            for ($i = 0; $i < 5; $i++) {
                District::create([
                    'province_id' => $province->id,
                    'name' => $faker->citySuffix,  // Sử dụng Faker để giả mạo tên quận/huyện
                ]);
            }
        }
    }
}
