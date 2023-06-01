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
        Product::factory()->count(1200)->create();

        // Product::create([
        //     'category_id' => 1,
        //     'nombre' => 'pantalla 21 pg',
        //     'codigo' => 'p10',
        //     'unidad' => 'unidades',
        //     'und' => 'unidades',
        //     'moneda' => 'soles', 
        //     'NoIGV' => 100,
        //     'maximo' => 100,
        //     'minimo' => 100,
        //     'SiIGV' => 118,
        //     'status' => 0,
        //     'tipo' => 'estandar',
        //     'unico' => 0,
        // ]); 
        // Product::create([
        //     'category_id' => 2,
        //     'nombre' => 'altavoz 120w',
        //     'codigo' => 'a120',
        //     'unidad' => 'unidades',
        //     'und' => 'unidades',
        //     'moneda' => 'dolares',
        //     'maximo' => 200,
        //     'minimo' => 200,
        //     'NoIGV' => 200,
        //     'SiIGV' => 236,
        //     'status' => 0,
        //     'tipo' => 'estandar',
        //     'unico' => 0,
        // ]); 
        // Product::create([
        //     'category_id' => 3,
        //     'nombre' => 'camara 1080',
        //     'codigo' => 'c10',
        //     'unidad' => 'unidades',
        //     'und' => 'unidades',
        //     'moneda' => 'soles',
        //     'maximo' => 1000,
        //     'minimo' => 1000,
        //     'NoIGV' => 1000,
        //     'SiIGV' => 1180,
        //     'status' => 0,
        //     'tipo' => 'estandar',
        //     'unico' => 0,
        // ]); 
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 1',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 2',
            'codigo' => 'c102',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 3',
            'codigo' => 'c103',
            'unidad' => 'unidades',
            'und' => 'unidades',
            'moneda' => 'dolares',
            'maximo' => 1000,
            'minimo' => 1000,
            'NoIGV' => 1000,
            'SiIGV' => 1180,
            'status' => 0,
            'tipo' => 'kit',
            'unico' => 0,
        ]); 
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 4',
            'codigo' => 'c104',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 5',
            'codigo' => 'c105',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 6',
            'codigo' => 'c106',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 7',
            'codigo' => 'c107',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 8',
            'codigo' => 'c108',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 8',
            'codigo' => 'c108',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit 9',
            'codigo' => 'c109',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit monitor 10',
            'codigo' => 'c1010',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit monitor 11',
            'codigo' => 'c1011',
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
        Product::create([
            'category_id' => 1,
            'nombre' => 'Kit monitor 12',
            'codigo' => 'c1012',
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
