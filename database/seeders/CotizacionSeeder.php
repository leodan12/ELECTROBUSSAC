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
        //Cotizacion::factory()->count(200)->create();
        Cotizacion::create([
            'moneda' => "dolares",
            'numero' => "00001",
            'formapago' => "credito", 
            'observacion' => "sin observacion",
            'costoventasinigv' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-05-05",
            'fechav' => "2023-05-19",
            'vendida' => "NO",
        ]); 
        Cotizacion::create([
            'moneda' => "soles",
            'numero' => "00002",
            'formapago' => "credito", 
            //'observacion' => "",
            'costoventasinigv' => 4600,
            'tasacambio' => 3.71,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-05-02",
            'fechav' => "2023-05-13",
           'vendida' => "NO",
        ]);
    }
}
