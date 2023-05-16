<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use App\Models\Detallecotizacion;

class DetallecotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Detallecotizacion::create([
            'cotizacion_id' => 1,
            'product_id' => 1,
            'cantidad' => 1, 
            'preciounitario' => 100,
            'preciounitariomo' => 100, 
            'servicio' => 0,
            'preciofinal' => 100,
            'observacionproducto' => 100,
        ]); 
        Detallecotizacion::create([
            'cotizacion_id' => 1,
            'product_id' => 3, 
            'cantidad' => 2,
            'preciounitario' => 1000,
            'preciounitariomo' => 1000, 
            'servicio' => 100,
            'preciofinal' => 2200,
            'observacionproducto' => 100,
        ]); 
        Detallecotizacion::create([
            'cotizacion_id' => 2,
            'product_id' => 2, 
            'cantidad' => 10,
            'preciounitario' => 200,
            'preciounitariomo' => 200, 
            'servicio' => 5,
            'preciofinal' => 2050,
            'observacionproducto' => 100,
        ]); 
        Detallecotizacion::create([
            'cotizacion_id' => 2,
            'product_id' => 1, 
            'cantidad' => 5,
            'preciounitario' => 100,
            'preciounitariomo' => 100, 
            'servicio' => 10,
            'preciofinal' => 550,
            'observacionproducto' => 100,
        ]);
        Detallecotizacion::create([
            'cotizacion_id' => 2,
            'product_id' => 3, 
            'cantidad' => 2,
            'preciounitario' => 1000,
            'preciounitariomo' => 1000, 
            'servicio' => 0,
            'preciofinal' => 2000,
            'observacionproducto' => 100,
        ]); 

    }
}
