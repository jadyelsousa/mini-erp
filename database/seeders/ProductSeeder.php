<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Popula as tabelas de produtos e estoque com dados de exemplo.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('pt_BR');

        $products = [
            [
                'name' => 'Camiseta Básica',
                'price' => 49.90,
                'variation' => 'Tamanho M',
                'quantity' => 100,
            ],
            [
                'name' => 'Calça Jeans',
                'price' => 129.90,
                'variation' => 'Tamanho 42',
                'quantity' => 50,
            ],
            [
                'name' => 'Tênis Esportivo',
                'price' => 199.99,
                'variation' => null,
                'quantity' => 30,
            ],
            [
                'name' => $faker->word . ' Eletrônico',
                'price' => $faker->randomFloat(2, 50, 999.99),
                'variation' => 'Cor Preta',
                'quantity' => $faker->numberBetween(10, 200),
            ],
            [
                'name' => $faker->word . ' Acessório',
                'price' => $faker->randomFloat(2, 10, 99.99),
                'variation' => null,
                'quantity' => $faker->numberBetween(20, 150),
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'name' => $productData['name'],
                'price' => $productData['price'],
                'variation' => $productData['variation'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Stock::create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
