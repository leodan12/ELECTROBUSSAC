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
            'numero' => "01",  
            'observacion' => "no",
            'costoventasinigv' => 2300,
            'costoventaconigv' => 2300,
            'tasacambio' => 3.8,
            'company_id' => 1,
            'cliente_id' => 2,
            'fecha' => "2023-05-02", 
            'fechav' => "2023-05-02", 
            'vendida' => "NO",
            'formapago' => "contado",
        ]); 
        Cotizacion::create([
            'moneda' => "soles",
            'numero' => "02",   
            'observacion' => "no",
            'costoventasinigv' => 4600,
            'costoventaconigv' => 2300,
            'tasacambio' => 3.71,
            'company_id' => 3,
            'cliente_id' => 4,
            'fecha' => "2023-05-02", 
            'fechav' => "2023-05-02", 
            'vendida' => "NO",
            'formapago' => "credito",
        ]); 
    }
}
