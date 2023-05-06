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
            'nombre' => 'ELECTROBUS SAC',
            'ruc' => '20477263284',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania1@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'MABEL ELIZABETH AGUIRRE ABANTO',
            'ruc' => '10180914892',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania2@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'DELMY RUFINO ALFARO ALFARO',
            'ruc' => '10178232024',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania3@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'MARGORY JOSHELYN ALFARO AGUIRRE',
            'ruc' => '10712354998',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania4@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'TATIANA BELKER JARA DIEGUEZ',
            'ruc' => '10464286832',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);
          Company::create([
            'nombre' => 'EKIBUS EIRL',
            'ruc' => '20603295251',
            'direccion' => 'sin direccion',
            'telefono' => '987654321',
            'email' => 'compania5@gmail.com',
            'status' => 0,
          ]);

    }
}
