<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingreso;

class IngresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Ingreso::factory()->count(1000)->create();
        Ingreso::create([
            'moneda' => "dolares",
            'factura' => "00001",
            'formapago' => "credito", 
            'observacion' => "sin observacion",
            'costoventa' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-06-03",
            'fechav' => "2023-06-02",
            'pagada' => "NO",
        ]); 
        Ingreso::create([
            'moneda' => "soles",
            'factura' => "00002",
            'formapago' => "contado", 
            //'observacion' => "",
            'costoventa' => 4600,
            'tasacambio' => 3.71,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-06-06",
           // 'fechav' => "2023-05-02",
           'pagada' => "SI",
        ]); 
    }
}
