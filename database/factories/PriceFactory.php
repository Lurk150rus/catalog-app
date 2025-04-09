<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Price;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'id_product' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}
