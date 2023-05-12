<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\DB;

class CotizacionesController extends Controller
{
    public function index()
    {
       // $cotizaciones = Cotizacion::orderBy('id', 'desc')->get();
        $cotizaciones = DB::table('cotizacions as c')
        ->join('companies as e', 'c.company_id', '=', 'e.id')
        ->join('clientes as cl', 'c.cliente_id', '=', 'cl.id') 
        ->select('c.id','c.fecha','e.nombre as nombreempresa','cl.nombre as nombrecliente','c.moneda',
        'c.costoventa','c.vendida')
        ->get();
        //return $cotizaciones;
        return view('admin.cotizacion.index', compact('cotizaciones'));
    }

    public function destroy(int $cotizacion_id)
    {
        $cotizacion = Cotizacion::findOrFail($cotizacion_id);
        $cotizacion->delete();
        return redirect()->back()->with('message','Cotizacion Eliminada');
     
    }
}
