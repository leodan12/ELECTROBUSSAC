<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Venta;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Venta::class;

    public function definition(): array
    {
        $precio = $this->faker->numberBetween(200, 1000);
        $fecha = $this->faker->dateTimeBetween('2023-01-01','2023-05-30');
        $fecha->format('Y-m-d');
        $fecha1 = $this->faker->dateTimeBetween('2023-01-01','2023-05-30');
        $fecha1->format('Y-m-d');
        return [
            'factura' => $this->faker->numberBetween(110, 10000),
            'cliente_id' => $this->faker->numberBetween(1,500),
            'company_id' => $this->faker->numberBetween(1, 6),
            'fecha' => $fecha,
            'fechav' => $fecha1,
            'moneda' => "soles",
            'formapago' => "contado",
            'costoventa' => round($precio*1.18 ,2 ),
            'tasacambio' => 3.71,
            'pagada' => 'SI',
        ];
    }
}
