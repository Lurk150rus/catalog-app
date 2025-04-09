<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаём иерархию групп
        Group::factory()->count(5)->create(); // Корневые
        Group::factory()->count(15)->create(); // Подгруппы

        // Создаём продукты
        Product::factory()->count(30)->create()->each(function ($product) {
            // Для каждого продукта создаём цену
            Price::factory()->create([
                'id_product' => $product->id,
            ]);
        });
    }
}
