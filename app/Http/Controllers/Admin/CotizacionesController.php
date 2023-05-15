<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Product;
use App\Http\Requests\CotizacionFormRequest;
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
        'c.costoventasinigv','c.vendida','c.numero','c.formapago')
        ->get();
        //return $cotizaciones;
        return view('admin.cotizacion.index', compact('cotizaciones'));
    }

    public function create()
    {   $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.cotizacion.create',compact('companies','products','clientes'));
    }
    public function store(CotizacionFormRequest $request)
    {
        $fechahoy = date('Y-m-d'); 
        $año = substr( $fechahoy, 0,4);
        $mes = substr( $fechahoy, -5,2);;
        $dia = substr( $fechahoy, -2,2);
        $fechanum =  $año.$mes.$dia ;

        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
 
        $nrocotizaciones = DB::table('cotizacions as c')
        ->join('companies as e', 'c.company_id', '=', 'e.id')
        ->where('e.id','=',$company->id)  
        ->select('c.id','c.id as company_id')
        ->count(); 
        $CotizacionesConCeros = str_pad($nrocotizaciones+1, 3, "0", STR_PAD_LEFT);
        $EmpresaConCeros = str_pad($company->id, 2, "0", STR_PAD_LEFT);

       // return $nrocotizaciones;
        
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventasinigv = $validatedData['costoventasinigv'];   

        $cotizacion = new Cotizacion;

        $cotizacion->company_id = $company->id;
        $cotizacion->cliente_id = $cliente->id;
        $cotizacion->fecha = $fecha;
        $cotizacion->costoventasinigv = $costoventasinigv;  
        $cotizacion->costoventaconigv = $request->costoventaconigv;  
        $cotizacion->moneda = $moneda; 
        $cotizacion->vendida = "NO";
        $cotizacion->numero = $fechanum."-".$EmpresaConCeros."-".$CotizacionesConCeros;

        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio']; 
        $formapago = $validatedData['formapago']; 

        $cotizacion->tasacambio = $tasacambio;
        $cotizacion->observacion = $observacion;
        $cotizacion->fechav =$request->fechav;
        $cotizacion->formapago =$request->formapago;
        //guardamos la venta y los detalles
        $cotizacion->save() ;

        return redirect('admin/cotizacion')->with('message','Cotizacion Agregada Satisfactoriamente');

    }

    public function destroy(int $cotizacion_id)
    {
        $cotizacion = Cotizacion::findOrFail($cotizacion_id);
        $cotizacion->delete();
        return redirect()->back()->with('message','Cotizacion Eliminada');
     
    }
}
