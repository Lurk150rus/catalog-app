<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        $parent = Group::inRandomOrder()->first();

        return [
            'id_parent' => $parent?->id ?? 0,
            'name' => $this->faker->unique()->word(),
        ];
    }
}
