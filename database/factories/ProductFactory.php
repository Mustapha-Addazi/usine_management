<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->userName,
            'type'=>fake()->text(5),
            'unit'=>fake()->text(5),
            'covering'=>fake()->biasedNumberBetween(1,1000),
            'weightunit'=>fake()->biasedNumberBetween(1,500)
        ];
    }
}
