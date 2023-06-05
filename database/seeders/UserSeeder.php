<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    
    public function run() 
    {
        $usuarioadmin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
        ]);
        $roladmin = Role::create([
            'name' => 'Administrador',
        ]);
        $permisos = Permission::pluck('id', 'id')->all();
        $roladmin->syncPermissions($permisos);
        $usuarioadmin->assignRole([$roladmin->id]);


       
        $usuariouser = User::create([
            'name' => 'usuario1',
            'email' => 'usuario1@gmail.com',
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
        ]);
        $roluser =  Role::create([
            'name' => 'Usuario',  
        ]);
        $permisosuser = ['ver-cliente','crear-cliente','editar-cliente','eliminar-cliente'];
 
        $roluser->syncPermissions($permisosuser);
        $usuariouser->assignRole([$roluser->id]);
    }
}
