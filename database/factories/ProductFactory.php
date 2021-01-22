<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => null,
            'name' => ucwords($this->faker->words(rand(1, 4), true)),
            'price' => $this->faker->numberBetween(5, 500) . "000",
            'status' => rand(0, 1) === 1 ? 'Y' : 'N',
        ];
    }
}
