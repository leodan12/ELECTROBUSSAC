<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Detalleinventario;
class DetalleInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Detalleinventario::create([
            'inventario_id' => 1,
            'company_id' => 1,
            'stockempresa' => 10, 
            'status' => 0,
        ]); 
        Detalleinventario::create([
            'inventario_id' => 1,
            'company_id' => 2,
            'stockempresa' => 10, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 1,
            'company_id' => 3,
            'stockempresa' => 10, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 2,
            'company_id' => 3,
            'stockempresa' => 15, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 2,
            'company_id' => 4,
            'stockempresa' => 15, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 2,
            'company_id' => 5,
            'stockempresa' => 15, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 3,
            'company_id' => 2,
            'stockempresa' => 20, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 3,
            'company_id' => 3,
            'stockempresa' => 20, 
            'status' => 0,
        ]);
        Detalleinventario::create([
            'inventario_id' => 3,
            'company_id' => 4,
            'stockempresa' => 20, 
            'status' => 0,
        ]);
    }
}
