<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Popula o banco de dados com dados iniciais.
     *
     * @return void
     */
    public function run() : void
    {
        $this->call([
            ProductSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
