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
          Cliente::create([
            'nombre' => 'COMPAÑIA 1',
            'ruc' => '11111111111',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania1@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'COMPAÑIA 2',
            'ruc' => '22222222222',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania2@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'COMPAÑIA 3',
            'ruc' => '33333333333',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania3@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'COMPAÑIA 4',
            'ruc' => '44444444444',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania4@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'COMPAÑIA 5',
            'ruc' => '55555555555',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
    }
}
