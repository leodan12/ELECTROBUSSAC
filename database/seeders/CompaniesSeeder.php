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
            'ruc' => '10234567854',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania1@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 2',
            'ruc' => '10457848954',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania2@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 3',
            'ruc' => '10896544724',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania3@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 4',
            'ruc' => '10945643254',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania4@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'COMPAÑIA 5',
            'ruc' => '10945632544',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
    }
}
