<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dato;

class DatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Dato::create([
            'nombre' => 'tasacambio',
            'valor' => '3.71',
            'fecha' => '2023-07-12',
        ]); 
        
  
    }
}
