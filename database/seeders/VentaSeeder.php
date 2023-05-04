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
        Venta::create([
            'moneda' => "dolares",
            'factura' => "00001",
            'formapago' => "credito", 
            'observacion' => "sin observacion",
            'costoventa' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-05-02",
            'fechav' => "2023-05-02",
        ]); 
        Venta::create([
            'moneda' => "soles",
            'factura' => "00002",
            'formapago' => "contado", 
            //'observacion' => "",
            'costoventa' => 4600,
           // 'tasacambio' => ,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-05-02",
           // 'fechav' => "2023-05-02",
        ]); 
        
    }
}
