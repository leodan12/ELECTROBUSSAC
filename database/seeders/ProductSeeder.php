<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Product::create([
            'category_id' => 1,
            'nombre' => 'pantalla 21 pg',
            'codigo' => 'p10',
            'unidad' => 'unidades',
            'und' => 'unidades',
            'moneda' => 'soles', 
            'NoIGV' => 100,
            'maximo' => 100,
            'minimo' => 100,
            'SiIGV' => 118,
            'status' => 0,
            'tipo' => 'estandar',
            'unico' => 0,
        ]); 
        Product::create([
            'category_id' => 2,
            'nombre' => 'altavoz 120w',
            'codigo' => 'a120',
            'unidad' => 'unidades',
            'und' => 'unidades',
            'moneda' => 'dolares',
            'maximo' => 200,
            'minimo' => 200,
            'NoIGV' => 200,
            'SiIGV' => 236,
            'status' => 0,
            'tipo' => 'estandar',
            'unico' => 0,
        ]); 
        Product::create([
            'category_id' => 3,
            'nombre' => 'camara 1080',
            'codigo' => 'c10',
            'unidad' => 'unidades',
            'und' => 'unidades',
            'moneda' => 'soles',
            'maximo' => 1000,
            'minimo' => 1000,
            'NoIGV' => 1000,
            'SiIGV' => 1180,
            'status' => 0,
            'tipo' => 'estandar',
            'unico' => 0,
        ]); 
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit monitor escritorio',
            'codigo' => 'c101',
            'unidad' => 'unidades',
            'und' => 'unidades',
            'moneda' => 'soles',
            'maximo' => 1000,
            'minimo' => 1000,
            'NoIGV' => 1000,
            'SiIGV' => 1180,
            'status' => 0,
            'tipo' => 'kit',
            'unico' => 0,
        ]); 
        
    }
}
