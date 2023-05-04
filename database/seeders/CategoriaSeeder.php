<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Category::create([
            'nombre' => 'PANTALLAS',
            'status' => 0,
        ]); 
        Category::create([
            'nombre' => 'ALTAVOCES',
            'status' => 0,
        ]); 
        Category::create([
            'nombre' => 'CAMARAS',
            'status' => 0,
        ]); 
  
    }
}
