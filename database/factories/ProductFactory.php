<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition(): array
    {
        $precio = $this->faker->numberBetween(50, 1000);
        return [
            'category_id' => $this->faker->numberBetween(1, 3),
            'nombre' => $this->faker->name(),
            'codigo' => $this->faker->numberBetween(1111, 9999),
            'unidad' => "unidad",
            'tipo' => "estandar",
            'moneda' => "soles",
            'NoIGV' => $precio,
            'SiIGV' => round($precio*1.18 ,2 ),
            'minimo' => $precio,
            'maximo' => $precio,
        ];
    }
}
