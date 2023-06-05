<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
USE Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            //para roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'eliminar-rol', 
            //para usuarios
            'ver-usuario',
            'crear-usuario',
            'editar-usuario',
            'eliminar-usuario',
            //para clientes
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',

        ];

        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}
