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
        $ingresos = Ingreso::orderBy('id', 'desc')->get();
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
       
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];
        $ingreso = new Ingreso;

        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha; 
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $factura;
        $ingreso->pagada = $pagada;
        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];

        $ingreso->observacion = $observacion;
        if($formapago== 'credito'){
            $ingreso->fechav = $fechav;
        }
        if($moneda== 'dolares'){
            $ingreso->tasacambio = $tasacambio;
        }
        
        //guardamos la venta y los detalles
        if (  $ingreso->save() ) {
            $product = $request->Lproduct;
            $observacionproducto = $request->Lobservacionproducto;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detalleingreso = new Detalleingreso;
                    $Detalleingreso->ingreso_id = $ingreso->id;
                    $Detalleingreso->product_id = $product[$i];
                    $Detalleingreso->observacionproducto = $observacionproducto[$i];
                    $Detalleingreso->cantidad = $cantidad[$i];
                    $Detalleingreso->preciounitario = $preciounitario[$i];
                    $Detalleingreso->preciounitariomo = $preciounitariomo[$i];
                    $Detalleingreso->servicio= $servicio[$i];
                    $Detalleingreso->preciofinal = $preciofinal[$i];
                    $Detalleingreso->save();
                }
                return redirect('admin/ingreso')->with('message','Ingreso Agregado Satisfactoriamente');
            }
            return redirect('admin/ingreso')->with('message','Ingreso Agregado Satisfactoriamente');
        }
        
    }

    public function update(IngresoFormRequest $request ,int $ingreso_id)
    {
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
       
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];

        $ingreso =  Ingreso::findOrFail($ingreso_id);

        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha; 
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $factura;
        $ingreso->pagada = $pagada;


        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];

        $ingreso->observacion = $observacion;
        if($formapago== 'credito'){
            $ingreso->fechav = $fechav;
        } elseif($formapago == 'contado'){
            $ingreso->fechav = null;
        }

        if($moneda== 'dolares'){
            $ingreso->tasacambio = $tasacambio;
        }elseif($moneda == 'soles'){
            $ingreso->tasacambio = null;
        }
        
        //guardamos la venta y los detalles
        if (  $ingreso->update() ) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detalleingreso = new Detalleingreso;
                    $Detalleingreso->ingreso_id = $ingreso->id;
                    $Detalleingreso->product_id = $product[$i];
                    $Detalleingreso->observacionproducto = $observacionproducto[$i];
                    $Detalleingreso->cantidad = $cantidad[$i];
                    $Detalleingreso->preciounitario = $preciounitario[$i];
                    $Detalleingreso->preciounitariomo = $preciounitariomo[$i];
                    $Detalleingreso->servicio= $servicio[$i];
                    $Detalleingreso->preciofinal = $preciofinal[$i];
                    $Detalleingreso->save();
                }
                return redirect('admin/ingreso')->with('message','Ingreso Actualizado Satisfactoriamente');
            }

        return redirect('admin/ingreso')->with('message','Ingreso Actualizado Satisfactoriamente');
        }
    } 

    public function edit(int $ingreso_id)
    {
        //$companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        $companies = DB::table('companies as c')
        ->join('ingresos as i', 'i.company_id', '=', 'c.id')
        ->select('c.id','c.nombre','c.ruc')
        ->where('i.id', '=', $ingreso_id)
        ->get();

       
        $ingreso = Ingreso::findOrFail($ingreso_id);
        $detallesingreso = DB::table('detalleingresos as di')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->select('di.observacionproducto','p.moneda','di.id as iddetalleingreso','di.cantidad', 'di.preciounitario','di.preciounitariomo','di.servicio','di.preciofinal','p.id as idproducto','p.nombre as producto')
            ->where('i.id', '=', $ingreso_id)->get();
        //return $detallesventa;
        return view('admin.ingreso.edit', compact('products','ingreso','companies','clientes','detallesingreso'));
    } 

    public function show($id)
    {

        $ingreso = DB::table('ingresos as i')
        ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
        ->join('companies as c', 'i.company_id', '=', 'c.id')
        ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
        ->join('products as p', 'di.product_id', '=', 'p.id')
        ->select(
            'i.fecha',
            'i.factura',
            'i.formapago',
            'i.moneda',
            'i.costoventa',
            'i.fechav',
            'i.tasacambio',
            'i.observacion',
            'i.moneda',
            'c.nombre as company',
            'cl.nombre as cliente',
            'p.nombre as producto',
            'di.cantidad',
            'di.preciounitario',
            'di.preciounitariomo',
            'di.servicio',
            'di.preciofinal',
            'di.observacionproducto',
            'p.moneda as monedaproducto',
            'i.pagada'
            
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

     public function pagarfactura($id)
     {
         //buscamos el registro con el id enviado por la URL
         $ingreso = Ingreso::find($id);
         if($ingreso){
             $ingreso->pagada = "SI";
             if($ingreso->update()){
                 return 1;
             }else { return 0;}
         }else { return 2;} 
     }

     public function destroydetalleingreso($id) 
     {
         //buscamos el registro con el id enviado por la URL
         $detalleingreso = Detalleingreso::find($id);
         if($detalleingreso){
             $ingreso = DB::table('detalleingresos as di')
                 ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                 ->select('i.costoventa','di.preciofinal','i.id')
                 ->where('di.id', '=', $id)->first();
             if($detalleingreso->delete()){
                 $costof = $ingreso->costoventa;
                 $detalle = $ingreso->preciofinal;
                 $idingreso = $ingreso->id;
                 
                 $ingresoedit = Ingreso::findOrFail($idingreso);
                 $ingresoedit->costoventa =$costof -$detalle;
                 $ingresoedit->update();
 
                 return 1;
             }else { return 0;}
         }else { return 2;}
         
 
 
     }
}
