<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dato;
use Illuminate\Support\Facades\DB;

class DatoController extends Controller
{

    public function vertasacambio()
    {
        $tasa = DB::table('datos as d')
            ->where('d.nombre', '=', 'tasacambio')
            ->select('d.nombre', 'd.valor', 'd.fecha', 'd.id')
            ->first();
        //return $tasa;
        $dato = collect();
        $dato->put('tasacambio', $tasa->nombre);
        $dato->put('valor', $tasa->valor);
        $dato->put('fecha', $tasa->fecha);
        $dato->put('id', $tasa->id);
        return $dato;
    }

    public function actualizartasacambio($tasacambio, $fecha, $id)
    {
        $tasa = Dato::find($id);
        if ($tasa) {
            $tasa->valor = $tasacambio;
            $tasa->fecha = $fecha;
            if ($tasa->update()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
