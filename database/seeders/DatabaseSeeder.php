<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(CategoriaSeeder::class); 
        $this->call(CompaniesSeeder::class); 
        $this->call(ClienteSeeder::class); 
        $this->call(ProductSeeder::class); 
        $this->call(UserSeeder::class); 
        $this->call(InventarioSeeder::class); 
        $this->call(DetalleInventarioSeeder::class); 
        $this->call(VentaSeeder::class); 
        $this->call(DetalleventaSeeder::class); 

    }
}
