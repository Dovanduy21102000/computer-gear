<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\District>
 */
class DistrictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,  // Tên quận huyện giả
            'code' => $this->faker->unique()->regexify('[A-Za-z]{3}[0-9]{2}'),  // Mã quận huyện giả
            'province_id' => Province::factory(), // Liên kết với một tỉnh thành
        ];
    }
}
