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
            //para categorias
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',
            //para productos
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',
            //para kits
            'ver-kit',
            'crear-kit',
            'editar-kit',
            'eliminar-kit',
            //para empresas
            'ver-empresa',
            'crear-empresa',
            'editar-empresa',
            'eliminar-empresa', 
            //para clientes
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',
            //para  inventarios
            'ver-inventario',
            'crear-inventario',
            'editar-inventario',
            'eliminar-inventario',
            //para cotizaciones 
            'ver-cotizacion',
            'crear-cotizacion',
            'editar-cotizacion',
            'eliminar-cotizacion',
            //para  compras o ingresos
            'ver-ingreso',
            'crear-ingreso',
            'editar-ingreso',
            'eliminar-ingreso',
            //para  ventas
            'ver-venta',
            'crear-venta',
            'editar-venta',
            'eliminar-venta',
            //para reportes
            'ver-reporte',
            //para recuperar los registros
            'recuperar-categoria',
            'recuperar-producto',
            'recuperar-kit',
            'recuperar-empresa',
            'recuperar-cliente',
            'recuperar-inventario',
            //para  HISTORIAL
            'ver-historial', 
            'eliminar-historial',
        ];

        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}
