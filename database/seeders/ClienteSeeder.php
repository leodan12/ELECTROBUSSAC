<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Cliente::create([
            'nombre' => 'CLIENTE 1',
            'ruc' => '202345678954',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'cliente1@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'CLIENTE 2',
            'ruc' => '2045788954',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'cliente2@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'CLIENTE 3',
            'ruc' => '2089654724',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'cliente3@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'CLIENTE 4',
            'ruc' => '2094563254',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'cliente4@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'CLIENTE 5',
            'ruc' => '2094563254',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'cliente5@gmail.com',
            'status' => 0,
          ]);
    }
}
