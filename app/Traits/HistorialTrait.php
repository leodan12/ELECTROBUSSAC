<?php
namespace App\Traits;
use App\Models\Historial;
use DateTime;
use Illuminate\Support\Facades\Auth;


trait HistorialTrait {
    public function crearhistorial($accion, $idregistro, $dato1, $dato2,$tabla)
    {
        $usuario = Auth::user()->id;
        $fecha = Date('Y-m-d H:i:s');
        $historial = new Historial;
        $historial->accion = $accion;
        $historial->registro_id = $idregistro;
        $historial->dato1 = $dato1;
        $historial->dato2 = $dato2;
        $historial->fecha = $fecha;
        $historial->usuario_id = $usuario;
        $historial->tabla = $tabla;
        $historial->save();
    }
}
