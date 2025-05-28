<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::now();

        for ($i = 0; $i < 40; $i++) {
            $randomDate = $faker->dateTimeBetween($startDate, $endDate);

            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->optional()->phoneNumber,
                'avatar' => null,
                'verify_token' => null,
                'status' => 'active',
                'email_verified_at' => $randomDate,
                'password' => Hash::make('123456'),
                'role' => 'member',
                'remember_token' => Str::random(10),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
                'deleted_at' => null,
                'address' => $faker->address,
            ]);
        }
    }
}
