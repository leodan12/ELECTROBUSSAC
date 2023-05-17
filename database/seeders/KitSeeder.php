<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kit;

class KitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kit::create([
            'product_id' => 4,
            'cantidad' => 2,
            'preciounitario' => 100,
            'preciounitariomo' => 100,
            'preciofinal' => 200,
            'kitproduct_id' => 1,
        ]);
        Kit::create([
            'product_id' => 4,
            'cantidad' => 3,
            'preciounitario' => 1000,
            'preciounitariomo' => 1000,
            'preciofinal' => 3000,
            'kitproduct_id' => 3
        ]);
    }
}
