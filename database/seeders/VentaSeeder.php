<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venta;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         //Venta::factory()->count(1000)->create();
        Venta::create([
            'moneda' => "dolares",
            // 'factura' => "00001",
            'formapago' => "credito", 
            'observacion' => "sin observacion",
            'costoventa' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-06-05",
            'fechav' => "2023-06-19",
            'pagada' => "NO",
        ]); 
        Venta::create([
            'moneda' => "soles",
            // 'factura' => "00002",
            'formapago' => "credito", 
            //'observacion' => "",
            'costoventa' => 4600,
            'tasacambio' => 3.71,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-06-02",
            'fechav' => "2023-06-13",
           'pagada' => "NO",
        ]); 
        
    }
}
