<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventario;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventario>
 */
class InventarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Inventario::class;
    var $cont =0;

    public function definition(): array
    { 
        return [
            'product_id' => $this->faker->unique()->numberBetween(1, Product::count()),
            'stockminimo' => $this->faker->numberBetween(1, 9),
            'stocktotal' => $this->faker->numberBetween(100, 900),
             
        ];
    }
}
