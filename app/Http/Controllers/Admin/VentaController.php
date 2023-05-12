<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venta;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\Detalleventa;
use App\Models\Inventario;
use App\Models\Detalleinventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaFormRequest;
use PDF;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::orderBy('id', 'desc')->get();
        return view('admin.venta.index', compact('ventas'));
    }

    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.venta.create',compact('companies','products','clientes'));
    }

    public function store(VentaFormRequest $request)
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

        $venta = new Venta;

        $venta->company_id = $company->id;
        $venta->cliente_id = $cliente->id;
        $venta->fecha = $fecha;
        $venta->costoventa = $costoventa;
        $venta->formapago = $formapago;
        $venta->moneda = $moneda;
        $venta->factura = $factura;
        $venta->pagada = $pagada;

        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];

        $venta->tasacambio = $tasacambio;
        $venta->observacion = $observacion;
        if($formapago== 'credito'){
            $venta->fechav = $fechav;
        }
        //guardamos la venta y los detalles
        if (  $venta->save() ) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detalleventa = new Detalleventa;
                    $Detalleventa->venta_id = $venta->id;
                    $Detalleventa->product_id = $product[$i];
                    $Detalleventa->cantidad = $cantidad[$i];
                    $Detalleventa->observacionproducto = $observacionproducto[$i];
                    $Detalleventa->preciounitario = $preciounitario[$i];
                    $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                    $Detalleventa->servicio= $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if($Detalleventa->save()){
 
                        $detalle = DB::table('detalleinventarios as di')
                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                        ->where('i.product_id', '=', $product[$i])
                        ->where('di.company_id', '=', $company->id)
                        ->select('di.id')
                        ->first();
 
                        $detalleinventario = Detalleinventario::find($detalle->id);
                        if($detalleinventario){
                        $mistock=(($detalleinventario->stockempresa) - $cantidad[$i]);
                        $detalleinventario->stockempresa = $mistock ;
                        if($detalleinventario->update()){ 
                            $inventario = Inventario::find($detalleinventario->inventario_id);
                            $mistockt = $inventario->stocktotal - $cantidad[$i];
                            $inventario->stocktotal = $mistockt;
                            $inventario->update();
                        }
                        }
                    }
                }
                return redirect('admin/venta')->with('message','Venta Agregada Satisfactoriamente');
            }
            return redirect('admin/venta')->with('message','Venta Agregada Satisfactoriamente');
        }

    }

    public function edit(int $venta_id)
    {

        $venta = Venta::findOrFail($venta_id);
        //$companies = Company::all();
        $companies = DB::table('companies as c')
        ->join('ventas as v', 'v.company_id', '=', 'c.id')
        ->select('c.id','c.nombre','c.ruc')
        ->where('v.id', '=', $venta_id)
        ->get();
        $clientes = Cliente::all();
        //$products = Product::all();

        $products = DB::table('detalleinventarios as di')
                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                ->join('companies as c', 'di.company_id', '=', 'c.id')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->select('p.id','p.nombre','p.NoIGV','di.stockempresa','p.moneda')
                ->where('c.id', '=', $venta->company_id)
                ->where('di.stockempresa', '>', 0)
                ->get();


        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select('dv.observacionproducto','p.moneda','dv.id as iddetalleventa','dv.cantidad', 'dv.preciounitario','dv.preciounitariomo','dv.servicio','dv.preciofinal','p.id as idproducto','p.nombre as producto')
            ->where('v.id', '=', $venta_id)->get();
        //return $detallesventa;
        return view('admin.venta.edit', compact('products','venta','companies','clientes','detallesventa'));
    }

    public function update(VentaFormRequest $request ,int $venta_id)
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

        $venta =  Venta::findOrFail($venta_id);

        $venta->company_id = $company->id;
        $venta->cliente_id = $cliente->id;
        $venta->fecha = $fecha;
        $venta->costoventa = $costoventa;
        $venta->formapago = $formapago;
        $venta->moneda = $moneda;
        $venta->factura = $factura;
        $venta->pagada = $pagada;

        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $venta->tasacambio = $tasacambio;

        $venta->observacion = $observacion;
        if($formapago== 'credito'){
            $venta->fechav = $fechav;
        } elseif($formapago == 'contado'){
            $venta->fechav = null;
        }
        //guardamos la venta y los detalles
        if (  $venta->update() ) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detalleventa = new Detalleventa;
                    $Detalleventa->venta_id = $venta->id;
                    $Detalleventa->product_id = $product[$i];
                    $Detalleventa->cantidad = $cantidad[$i];
                    $Detalleventa->observacionproducto = $observacionproducto[$i];
                    $Detalleventa->preciounitario = $preciounitario[$i];
                    $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                    $Detalleventa->servicio= $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if($Detalleventa->save()){
 
                        $detalle = DB::table('detalleinventarios as di')
                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                        ->where('i.product_id', '=', $product[$i])
                        ->where('di.company_id', '=', $company->id)
                        ->select('di.id')
                        ->first();
 
                        $detalleinventario = Detalleinventario::findOrFail($detalle->id);
                        if($detalleinventario){
                        $mistock=(($detalleinventario->stockempresa) - $cantidad[$i]);
                        $detalleinventario->stockempresa = $mistock ;
                        if($detalleinventario->update()){ 
                            $inventario = Inventario::find($detalleinventario->inventario_id);
                            $mistockt = $inventario->stocktotal - $cantidad[$i];
                            $inventario->stocktotal = $mistockt;
                            $inventario->update();
                        }
                        }
                    }
                }
                return redirect('admin/venta')->with('message','Venta Actualizada Satisfactoriamente');
            }

        return redirect('admin/venta')->with('message','Venta Actualizada Satisfactoriamente');
        }
    }

    public function show($id)
    {

        $venta = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as c', 'v.company_id', '=', 'c.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')

            ->select(
                'v.fecha',
                'v.factura',
                'v.formapago',
                'v.moneda',
                'v.costoventa',
                'v.fechav',
                'v.tasacambio',
                'v.observacion',
                'p.moneda as monedaproducto',
                'c.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'dv.cantidad',
                'dv.preciounitario',
                'dv.preciounitariomo',
                'dv.servicio',
                'dv.preciofinal',
                'dv.observacionproducto',
                'v.pagada'

            )
            ->where('v.id', '=', $id)->get();

        return  $venta;
    }

    public function destroy(int $venta_id)
    {
        $venta = Venta::findOrFail($venta_id);
        $detallesventa = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->select('dv.cantidad','dv.product_id')
                ->where('v.id', '=', $venta_id)->get();
        for( $i = 0 ;$i < count($detallesventa); $i++){ 
            $detallesinventario=DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->select('di.id','di.company_id','di.stockempresa','i.product_id','di.inventario_id')
            //->where('i.id', '=', $venta_id)
            ->where('i.product_id', '=', $detallesventa[$i]->product_id)
            ->where('di.company_id', '=', $venta->company_id)
            ->first();

            $detalleinv = Detalleinventario::find($detallesinventario->id); 
            $inventario = Inventario::find($detallesinventario->inventario_id);
            
            if($detalleinv){
                $detalleinv->stockempresa = $detalleinv->stockempresa + $detallesventa[$i]->cantidad; 
                if($detalleinv->update()){
                    $inventario->stocktotal = $inventario->stocktotal + $detallesventa[$i]->cantidad; 
                    $inventario->update();
            }
        }
    }
        $venta->delete();
        return redirect()->back()->with('message','Venta Eliminada');
     
    }
    public function destroydetalleventa($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleventa = Detalleventa::find($id);
        if($detalleventa){
            $venta = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->select('dv.cantidad','v.costoventa','dv.preciofinal','v.id','v.company_id as idempresa','dv.product_id as idproducto')
                ->where('dv.id', '=', $id)->first();
            if($detalleventa->delete()){
                $costof = $venta->costoventa;
                $detalle = $venta->preciofinal;
                $idventa = $venta->id; 

                $ventaedit = Venta::findOrFail($idventa);
                $ventaedit->costoventa =$costof -$detalle;
                if($ventaedit->update()){
                    $detalleInv = DB::table('detalleinventarios as di')
                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                    ->where('i.product_id', '=', $venta->idproducto)
                    ->where('di.company_id', '=', $venta->idempresa)
                    ->select('di.id','i.stocktotal')
                    ->first();
                    $detalleinventario = Detalleinventario::findOrFail($detalleInv->id);
                    if($detalleinventario){
                        $mistock2 = $detalleinventario->stockempresa + $venta->cantidad  ; 
                        $detalleinventario->stockempresa = $mistock2;
                        if($detalleinventario->update()){
                            $inventario = Inventario::find($detalleinventario->inventario_id);
                            $mistockt = $inventario->stocktotal + $venta->cantidad;
                            $inventario->stocktotal = $mistockt;
                            $inventario->update();   }
                    }
                } 
                return 1;
            }else { return 0;}
        
        }else { return 2;}
    }

    public function pagarfactura($id)
    {
        //buscamos el registro con el id enviado por la URL
        $venta = Venta::find($id);
        if($venta){
            $venta->pagada = "SI";
            if($venta->update()){
                return 1;
            }else { return 0;}
        }else { return 2;}
    }

    public function productosxempresa($id)
    {
        //buscamos el registro con el id enviado por la URL
        //$empresa = Company::find($id);
        $products = DB::table('detalleinventarios as di')
                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                ->join('companies as c', 'di.company_id', '=', 'c.id')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->select('p.id','p.nombre','p.NoIGV','di.stockempresa','p.moneda')
                ->where('c.id', '=', $id)
                ->where('p.status', '=', 0)
                ->where('di.stockempresa', '>', 0)->get();

        return $products;
    }

    public function comboempresacliente($id)
    {
        //buscamos el registro con el id enviado por la URL
        $empresa = Company::find($id);

        $products = DB::table('clientes as c')
                ->select('c.id','c.nombre')
                ->where('c.ruc', '!=', $empresa->ruc)->get();

        return $products;
    }

    public function generarfacturapdf($id){

        $venta = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->join('companies as c', 'v.company_id', '=', 'c.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->select(
                'v.id as idventa',
                'v.fecha',
                'p.nombre as nombreproducto',
                'dv.cantidad',
                'dv.preciounitariomo',
                'dv.preciounitario',
                'dv.observacionproducto',
                'dv.servicio',
                'dv.preciofinal',
                'v.moneda as monedaventa',
                'p.moneda as monedaproducto',
                'v.formapago',
                'v.costoventa',
                'v.tasacambio',
                'v.costoventa',
                'c.nombre as company',
                'c.ruc as ruccompany',
                'c.direccion as direccioncompany',
                'c.telefono as telefonocompany',
                'cl.nombre as cliente',
                'cl.ruc as ruccliente',
                'cl.direccion as direccioncliente',
                'cl.telefono as telefonocliente'
                )
            ->where('v.id', '=', $id)->get();
        //return $venta;
        $pdf = PDF::loadView('admin.venta.facturapdf', ["venta" => $venta]);
        return $pdf->stream('venta.pdf'); 
    }
    


}
