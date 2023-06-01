<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Inventario::factory()->count(1200)->create();

        Inventario::create([
            'product_id' => 1,
            'stockminimo' => 5,
            'stocktotal' => 30, 
            'status' => 0,
        ]); 
        Inventario::create([
            'product_id' => 2,
            'stockminimo' => 4,
            'stocktotal' => 45, 
            'status' => 0,
        ]); 
        Inventario::create([
            'product_id' => 3,
            'stockminimo' => 3,
            'stocktotal' => 60, 
            'status' => 0,
        ]); 
    }
}
