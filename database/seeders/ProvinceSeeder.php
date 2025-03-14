<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');  // Sử dụng Faker với locale Việt Nam

        // Danh sách các tỉnh thành của Việt Nam
        $provinces = [
            ['name' => 'Thành phố Hà Nội', 'code' => 1],
            ['name' => 'Tỉnh Hà Giang', 'code' => 2],
            ['name' => 'Tỉnh Cao Bằng', 'code' => 4],
            ['name' => 'Tỉnh Bắc Kạn', 'code' => 6],
            ['name' => 'Tỉnh Tuyên Quang', 'code' => 8],
            ['name' => 'Tỉnh Lào Cai', 'code' => 10],
            ['name' => 'Tỉnh Điện Biên', 'code' => 11],
            ['name' => 'Tỉnh Lai Châu', 'code' => 12],
            ['name' => 'Tỉnh Sơn La', 'code' => 14],
            ['name' => 'Tỉnh Yên Bái', 'code' => 15],
            ['name' => 'Tỉnh Hoà Bình', 'code' => 17],
            ['name' => 'Tỉnh Thái Nguyên', 'code' => 19],
            ['name' => 'Tỉnh Lạng Sơn', 'code' => 20],
            ['name' => 'Tỉnh Quảng Ninh', 'code' => 22],
            ['name' => 'Tỉnh Bắc Giang', 'code' => 24],
            ['name' => 'Tỉnh Phú Thọ', 'code' => 25],
            ['name' => 'Tỉnh Vĩnh Phúc', 'code' => 26],
            ['name' => 'Tỉnh Bắc Ninh', 'code' => 27],
        ];

        foreach ($provinces as $province) {
            Province::create([
                'name' => $province['name'],
                'code' => $province['code'],
            ]);
        }
    }
}
