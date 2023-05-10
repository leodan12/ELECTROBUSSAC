<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venta;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\Detalleventa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaFormRequest;

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
                    $Detalleventa->save();
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
                ->where('c.id', '=', $venta->company_id)->get();

       
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
                    $Detalleventa->save();
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
                ->select('v.costoventa','dv.preciofinal','v.id')
                ->where('dv.id', '=', $id)->first();
            if($detalleventa->delete()){
                $costof = $venta->costoventa;
                $detalle = $venta->preciofinal;
                $idventa = $venta->id;
                
               
                $ventaedit = Venta::findOrFail($idventa);
                $ventaedit->costoventa =$costof -$detalle;
                $ventaedit->update();
 
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

    
}
