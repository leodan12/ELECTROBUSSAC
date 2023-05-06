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
            'nombre' => 'ELECTROBUS SAC',
            'ruc' => '20477263284',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania1@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'MABEL ELIZABETH AGUIRRE ABANTO',
            'ruc' => '10180914892',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania2@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'DELMY RUFINO ALFARO ALFARO',
            'ruc' => '10178232024',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania3@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'MARGORY JOSHELYN ALFARO AGUIRRE',
            'ruc' => '10712354998',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania4@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'TATIANA BELKER JARA DIEGUEZ',
            'ruc' => '10464286832',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
          Cliente::create([
            'nombre' => 'EKIBUS EIRL',
            'ruc' => '20603295251',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
    }
}
