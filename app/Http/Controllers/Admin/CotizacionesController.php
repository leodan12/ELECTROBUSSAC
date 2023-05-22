<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\Detallecotizacion;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Condicion;
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
            ->select(
                'c.id',
                'c.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'c.moneda',
                'c.costoventasinigv',
                'c.costoventaconigv',
                'c.vendida',
                'c.numero',
                'c.formapago'
            )
            ->get();
        //return $cotizaciones;
        return view('admin.cotizacion.index', compact('cotizaciones'));
    }

    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.cotizacion.create', compact('companies', 'products', 'clientes'));
    }
    public function store(CotizacionFormRequest $request)
    {
        $fechahoy = date('Y-m-d');
        $año = substr($fechahoy, 0, 4);
        $mes = substr($fechahoy, -5, 2);;
        $dia = substr($fechahoy, -2, 2);
        $fechanum =  $año . $mes . $dia;

        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);

        $nrocotizaciones = DB::table('cotizacions as c')
            ->join('companies as e', 'c.company_id', '=', 'e.id')
            ->where('e.id', '=', $company->id)
            ->select('c.id', 'e.id as company_id')
            ->count();
        $nroultimacotizacion = 0;
        if ($nrocotizaciones > 0) {
            $ultimacotizacion = DB::table('cotizacions as c')
                ->join('companies as e', 'c.company_id', '=', 'e.id')
                ->where('e.id', '=', $company->id)
                ->select('c.id', 'c.numero')
                ->orderBy('c.id', 'desc')
                ->first();
            global  $nroultimacotizacion;
            $nroultimacotizacion = substr($ultimacotizacion->numero, 12);

            //return  $nroultimacotizacion;

        }
        $CotizacionesConCeros = str_pad($nroultimacotizacion + 1, 3, "0", STR_PAD_LEFT);
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
        $cotizacion->numero = $fechanum . "-" . $EmpresaConCeros . "-" . $CotizacionesConCeros;

        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $formapago = $validatedData['formapago'];

        $cotizacion->tasacambio = $tasacambio;
        $cotizacion->observacion = $observacion;
        $cotizacion->fechav = $request->fechav;
        $cotizacion->formapago = $formapago;
        //guardamos la venta y los detalles
        if ($cotizacion->save()) {
            //traemos y guardamos los detalles de la cotizacion
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;

            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detallecotizacion = new Detallecotizacion;
                    $Detallecotizacion->cotizacion_id = $cotizacion->id;
                    $Detallecotizacion->product_id = $product[$i];
                    $Detallecotizacion->cantidad = $cantidad[$i];
                    $Detallecotizacion->observacionproducto = $observacionproducto[$i];
                    $Detallecotizacion->preciounitario = $preciounitario[$i];
                    $Detallecotizacion->preciounitariomo = $preciounitariomo[$i];
                    $Detallecotizacion->servicio = $servicio[$i];
                    $Detallecotizacion->preciofinal = $preciofinal[$i];
                    if ($Detallecotizacion->save()) {
                    }
                }
            }
            //traemos y guardamos las condiciones
            $condicion = $request->Lcondicion;
            if ($condicion !== null) {
                for ($i = 0; $i < count($condicion); $i++) {
                    $condicioncotizacion = new Condicion;
                    $condicioncotizacion->cotizacion_id = $cotizacion->id;
                    $condicioncotizacion->condicion = $condicion[$i];
                    $condicioncotizacion->save();
                }
            }
            return redirect('admin/cotizacion')->with('message', 'Cotizacion Agregada Satisfactoriamente');
        }
        return redirect('admin/cotizacion')->with('message', 'No se pudo Agregar la Cotizacion');
    }

    public function edit(int $cotizacion_id)
    {

        $cotizacion = Cotizacion::findOrFail($cotizacion_id);
        //$companies = Company::all();
        $companies = DB::table('companies as c')
            ->join('cotizacions as ct', 'ct.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('ct.id', '=', $cotizacion_id)
            ->get();
        $clientes = Cliente::all();
        //$products = Product::all();

        $products = DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre', 'p.NoIGV', 'di.stockempresa', 'p.moneda')
            ->where('c.id', '=', $cotizacion->company_id)
            ->where('di.stockempresa', '>', 0)
            ->get();


        $detallescotizacion = DB::table('detallecotizacions as dc')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->join('products as p', 'dc.product_id', '=', 'p.id')
            ->select('dc.observacionproducto', 'p.tipo', 'p.moneda', 'dc.id as iddetallecotizacion', 'dc.cantidad', 'dc.preciounitario', 'dc.preciounitariomo', 'dc.servicio', 'dc.preciofinal', 'p.id as idproducto', 'p.nombre as producto')
            ->where('c.id', '=', $cotizacion_id)->get();
        //return $detallesventa;
        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('detallecotizacions as dc', 'dc.product_id', '=', 'pv.id')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('c.id', '=', $cotizacion_id)->get();

        $condiciones = DB::table('condicions as cd')
            ->join('cotizacions as ct', 'cd.cotizacion_id', '=', 'ct.id')
            ->select('cd.id as idcondicion', 'cd.condicion')
            ->where('ct.id', '=', $cotizacion_id)->get();

        return view('admin.cotizacion.edit', compact('detalleskit', 'products', 'cotizacion', 'companies', 'clientes', 'detallescotizacion', 'condiciones'));
    }

    public function update(CotizacionFormRequest $request, int $cotizacion_id)
    {
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);

        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventasinigv = $validatedData['costoventasinigv'];

        $cotizacion =  Cotizacion::findOrFail($cotizacion_id);

        $cotizacion->company_id = $company->id;
        $cotizacion->cliente_id = $cliente->id;
        $cotizacion->fecha = $fecha;
        $cotizacion->costoventasinigv = $costoventasinigv;
        $cotizacion->costoventaconigv = $request->costoventaconigv;
        $cotizacion->moneda = $moneda;
        $cotizacion->numero = $request->numero;
        $cotizacion->vendida = "NO";

        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $formapago = $validatedData['formapago'];
        $cotizacion->tasacambio = $tasacambio;
        $cotizacion->formapago = $formapago;
        $cotizacion->observacion = $observacion;
        $cotizacion->fechav = $request->fechav;
        //guardamos la venta y los detalles

        if ($cotizacion->update()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detallecotizacion = new Detallecotizacion;
                    $Detallecotizacion->cotizacion_id = $cotizacion->id;
                    $Detallecotizacion->product_id = $product[$i];
                    $Detallecotizacion->cantidad = $cantidad[$i];
                    $Detallecotizacion->observacionproducto = $observacionproducto[$i];
                    $Detallecotizacion->preciounitario = $preciounitario[$i];
                    $Detallecotizacion->preciounitariomo = $preciounitariomo[$i];
                    $Detallecotizacion->servicio = $servicio[$i];
                    $Detallecotizacion->preciofinal = $preciofinal[$i];
                    $Detallecotizacion->save();
                }
            }
            //traemos y guardamos las condiciones
            $condicion = $request->Lcondicion;
            if ($condicion !== null) {
                for ($i = 0; $i < count($condicion); $i++) {
                    $condicioncotizacion = new Condicion;
                    $condicioncotizacion->cotizacion_id = $cotizacion->id;
                    $condicioncotizacion->condicion = $condicion[$i];
                    $condicioncotizacion->save();
                }
            }
            return redirect('admin/cotizacion')->with('message', 'Cotizacion Actualizada Satisfactoriamente');
        }
        return redirect('admin/cotizacion')->with('message', 'No se pudo Actualizar la cotizacion');
    }

    public function show($id)
    {
        $cotizacion = DB::table('cotizacions as c')
            ->join('detallecotizacions as dc', 'dc.cotizacion_id', '=', 'c.id')
            ->join('companies as e', 'c.company_id', '=', 'e.id')
            ->join('clientes as cl', 'c.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dc.product_id', '=', 'p.id')
            ->select(
                'c.fecha',
                'c.fechav',
                'c.numero',
                'c.formapago',
                'c.moneda',
                'c.costoventasinigv',
                'c.costoventaconigv',
                'c.tasacambio',
                'c.observacion',
                'p.moneda as monedaproducto',
                'e.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'dc.cantidad',
                'dc.preciounitario',
                'dc.preciounitariomo',
                'dc.servicio',
                'dc.preciofinal',
                'dc.observacionproducto',
                'c.vendida',
                'p.tipo as tipo',
                'p.id as idproducto',
            )
            ->where('c.id', '=', $id)->get();

        return  $cotizacion;
    }
    public function showcondiciones($id)
    {
        $condicion = DB::table('cotizacions as c')
            ->join('condicions as cd', 'cd.cotizacion_id', '=', 'c.id')
            ->select(
                'cd.condicion',
                'cd.id',
            )
            ->where('c.id', '=', $id)->get();

        return  $condicion;
    }

    public function destroy(int $cotizacion_id)
    {
        $cotizacion = Cotizacion::findOrFail($cotizacion_id);
        $cotizacion->delete();
        return redirect()->back()->with('message', 'Cotizacion Eliminada');
    }

    public function destroycondicion(int $condicion_id)
    {
        $condicion = Condicion::find($condicion_id);
        if ($condicion) {
            if ($condicion->delete()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function destroydetallecotizacion($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detallecotizacion = Detallecotizacion::find($id);
        if ($detallecotizacion) {
            $cotizacion = DB::table('detallecotizacions as dc')
                ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
                ->select('c.id', 'dc.preciofinal', 'c.costoventasinigv')
                ->where('dc.id', '=', $id)->first();

            if ($detallecotizacion->delete()) {
                $costof = $cotizacion->costoventasinigv;
                $detalle = $cotizacion->preciofinal;

                $editcotizacion = Cotizacion::findOrFail($cotizacion->id);
                $editcotizacion->costoventasinigv = $costof - $detalle;
                $editcotizacion->costoventaconigv = round(($costof - $detalle) * 1.18, 2);
                $editcotizacion->update();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
}
