<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Province>
 */
class ProvinceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->state,  // Tên tỉnh thành phố giả
            'code' => $this->faker->unique()->regexify('[A-Za-z]{2}[0-9]{2}'),  // Mã tỉnh thành phố giả
        ];
    }
}
