<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        Company::create([
            'nombre' => 'COMPAÑIA 1',
            'ruc' => '11111111111',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania1@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 2',
            'ruc' => '22222222222',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania2@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 3',
            'ruc' => '33333333333',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania3@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 4',
            'ruc' => '44444444444',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania4@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 5',
            'ruc' => '55555555555',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
    }
}
