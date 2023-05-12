<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cotizacion;

class CotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cotizacion::create([
            'moneda' => "dolares",  
            'observacion' => "no",
            'costoventa' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-05-02", 
            'vendida' => "NO",
        ]); 
        Cotizacion::create([
            'moneda' => "soles",  
            'observacion' => "no",
            'costoventa' => 4600,
            'tasacambio' => 3.71,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-05-02", 
            'vendida' => "NO",
        ]); 
    }
}
