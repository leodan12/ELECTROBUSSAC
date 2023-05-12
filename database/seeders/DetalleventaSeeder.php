<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Detalleventa;

class DetalleventaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Detalleventa::create([
            'venta_id' => 1,
            'product_id' => 1,
            'cantidad' => 1,
            'observacionproducto' => "no",
            'preciounitario' => 100,
            'preciounitariomo' => 100, 
            'servicio' => 0,
            'preciofinal' => 100,
        ]); 
        Detalleventa::create([
            'venta_id' => 1,
            'product_id' => 3,
            'observacionproducto' => "no",
            'cantidad' => 2,
            'preciounitario' => 1000,
            'preciounitariomo' => 1000, 
            'servicio' => 100,
            'preciofinal' => 2200,
        ]); 
        Detalleventa::create([
            'venta_id' => 2,
            'product_id' => 2,
            'observacionproducto' => "no",
            'cantidad' => 10,
            'preciounitario' => 200,
            'preciounitariomo' => 200, 
            'servicio' => 5,
            'preciofinal' => 2050,
        ]); 
        Detalleventa::create([
            'venta_id' => 2,
            'product_id' => 1,
            'observacionproducto' => "no",
            'cantidad' => 5,
            'preciounitario' => 100,
            'preciounitariomo' => 100, 
            'servicio' => 10,
            'preciofinal' => 550,
        ]);
        Detalleventa::create([
            'venta_id' => 2,
            'product_id' => 3,
            'observacionproducto' => "no",
            'cantidad' => 2,
            'preciounitario' => 1000,
            'preciounitariomo' => 1000, 
            'servicio' => 0,
            'preciofinal' => 2000,
        ]); 

    }
}
