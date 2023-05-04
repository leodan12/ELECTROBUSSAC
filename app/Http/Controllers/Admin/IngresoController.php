<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use App\Models\Company;
use App\Models\Ingreso;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Detalleingreso;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IngresoFormRequest;

class IngresoController extends Controller
{
    public function index()
    {
        $ingresos = Ingreso::all();
        return view('admin.ingreso.index', compact('ingresos'));
    }

    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.ingreso.create',compact('companies','products','clientes'));
    }

    public function store(IngresoFormRequest $request)
    {
        
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        /*$venta = $company->ventas()->create([
            'company_id' => $validatedData['company_id'],
            'cliente_id' => $validatedData['cliente_id'],
            'fecha' => $validatedData['fecha'],
            'moneda' => $validatedData['moneda'],
            'costoventa' => $validatedData['costoventa'],
        ]);*/

        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];

        $ingreso = new Ingreso;

        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->moneda = $moneda;
        $ingreso->costoventa = $costoventa;

        if ($ingreso->save()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detalleingreso = new Detalleingreso;
                    $Detalleingreso->venta_id = $ingreso->id;
                    $Detalleingreso->product_id = $product[$i];
                    $Detalleingreso->cantidad = $cantidad[$i];
                    $Detalleingreso->preciounitario = $preciounitario[$i];
                    $Detalleingreso->servicio= $servicio[$i];
                    $Detalleingreso->preciofinal = $preciofinal[$i];
                    $Detalleingreso->save();
                }
                return redirect('admin/ingreso')->with('message','Ingreso Agregado Satisfactoriamente');
            }
        }
       
    }

    public function show($id)
    {

        $ingreso = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as c', 'v.company_id', '=', 'c.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            
            ->select(
                'v.fecha',
                'v.moneda',
                'c.nombre',
                'cl.nombrecliente',
                'v.costoventa',
                'p.nombre as nombreproducto',
                'dv.cantidad',
                'p.SiIGV',
                'dv.servicio',
                'dv.preciofinal'
                
            )
            ->where('i.id', '=', $id)->get();

        return  $ingreso;
    }

    public function destroy(int $ingreso_id)
    {
        $ingreso = Ingreso::findOrFail($ingreso_id);
        $ingreso->delete();
        return redirect()->back()->with('message','Ingreso Eliminado');
     }

     public function destroydetalleingreso($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleingreso = Detalleingreso::find($id);
        $detalleingreso->delete();
    }
}
