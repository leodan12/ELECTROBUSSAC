<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cotizacion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cotizacion>
 */
class CotizacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Cotizacion::class;
    public function definition(): array
    {
        $precio = $this->faker->numberBetween(50, 1000);
        return [
            'numero' => $this->faker->numberBetween(110, 10000),
            'cliente_id' => $this->faker->numberBetween(1,500),
            'company_id' => $this->faker->numberBetween(1, 6),
            'fecha' => $this->faker->date(),
            'fechav' => $this->faker->date(),
            'moneda' => "soles",
            'formapago' => "contado",
            'costoventaconigv' => round($precio*1.18 ,2 ),
            'costoventasinigv' => $precio,
            'vendida' => 'no',
        ];
    }
}
