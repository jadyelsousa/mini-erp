<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Faker\Factory as Faker;

class CouponSeeder extends Seeder
{
    /**
     * Popula a tabela de cupons com dados de exemplo.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('pt_BR');

        $coupons = [
            [
                'code' => 'DESCONTO10',
                'active' => true,
                'valid_until' => now()->addDays(30),
                'min_value' => 50.00,
                'discount' => 10.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PROMO20',
                'active' => true,
                'valid_until' => now()->addDays(15),
                'min_value' => 100.00,
                'discount' => 20.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => $faker->unique()->lexify('CUPOM???'),
                'active' => false,
                'valid_until' => now()->subDays(5),
                'min_value' => $faker->randomFloat(2, 50, 200),
                'discount' => $faker->randomFloat(2, 5, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
