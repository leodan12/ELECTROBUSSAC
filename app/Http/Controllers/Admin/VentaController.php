<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venta;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Product;
use App\Models\Detalleventa;
use App\Models\Inventario;
use App\Models\Detalleingreso;
use App\Models\Ingreso;
use App\Models\Detalleinventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaFormRequest;
use Illuminate\Support\Collection;
use PDF;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class VentaController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-venta|editar-venta|crear-venta|eliminar-venta',
            ['only' => ['index', 'show', 'showcreditos', 'pagarfactura', 'generarfacturapdf']]
        );
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store', 'create2', 'facturadisponible']]);
        $this->middleware('permission:editar-venta', ['only' => ['edit', 'update', 'destroydetalleventa', 'misdetallesventa']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware(
            'permission:crear-venta|crear-cotizacion|crear-ingreso|editar-venta|editar-cotizacion|editar-ingreso|ver-venta|ver-ingreso|ver-cotizacion|eliminar-venta|eliminar-ingreso|eliminar-cotizacion',
            ['only' => [
                'productosxempresa', 'productosxkit', 'comboempresacliente', 'comboempresaclientevi',
                'stockkitxempresa', 'stockxprodxempresa', 'facturadisponible'
            ]]
        );
    }
    use HistorialTrait;
    public function index(Request $request)
    {
        $fechahoy = date('Y-m-d');
        $fechalimite =  date("Y-m-d", strtotime($fechahoy . "+ 7 days"));

        $creditosxvencer = DB::table('ventas as v')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->where('v.fechav', '!=', null)
            ->where('v.fechav', '<=', $fechalimite)
            ->where('v.pagada', '=', 'NO')
            ->select(
                'v.id',
                'v.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'v.moneda',
                'v.costoventa',
                'v.pagada',
                'v.fechav',
                'v.factura',
                'v.formapago'
            )
            ->count();
        $sinnumero = DB::table('ventas as v')
            ->where('v.factura', '=', null)
            ->select('v.id')
            ->count();

        if ($request->ajax()) {

            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->join('companies as e', 'v.company_id', '=', 'e.id')
                ->select(
                    'v.id',
                    'c.nombre as cliente',
                    'e.nombre as empresa',
                    'v.moneda',
                    'v.formapago',
                    'v.factura',
                    'v.costoventa',
                    'v.pagada',
                    'v.fecha',
                );
            return DataTables::of($ventas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ventas) {
                    return view('admin.venta.botones', compact('ventas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.venta.index', compact('creditosxvencer', 'sinnumero'));
    }
    public function index2()
    {
        return redirect('admin/venta')->with('verstock', 'Ver');
    }
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.venta.create', compact('companies', 'products', 'clientes'));
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

        if ($factura != null) {
            $nrofacturadisponible = $this->facturadisponible($company->id, $factura);
            if ($nrofacturadisponible == "NO") {
                return redirect()->back()->with('message', 'Numero de Factura YA Registrado');
            }
        }

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
        if ($formapago == 'credito') {
            $venta->fechav = $fechav;
        }
        //guardamos la venta y los detalles
        if ($venta->save()) {

            $idcotizacion = $request->idcotizacion;
            if ($idcotizacion != -1) {
                $cotizacion = Cotizacion::findOrFail($idcotizacion);
                $cotizacion->vendida = "SI";
                $cotizacion->update();
            }
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
                    $Detalleventa->servicio = $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if ($Detalleventa->save()) {

                        $miproductox = Product::find($product[$i]);
                        if ($miproductox && $miproductox->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();

                                $detalleinventario = Detalleinventario::find($detalle->id);
                                if ($detalleinventario) {
                                    $mistock = (($detalleinventario->stockempresa) - (($milistaproductos[$j]->cantidad) * $cantidad[$i]));
                                    $detalleinventario->stockempresa = $mistock;
                                    if ($detalleinventario->update()) {
                                        $inventario = Inventario::find($detalleinventario->inventario_id);
                                        $mistockt = $inventario->stocktotal -  (($milistaproductos[$j]->cantidad) * $cantidad[$i]);
                                        $inventario->stocktotal = $mistockt;
                                        $inventario->update();
                                    }
                                }
                            }
                        } else if ($miproductox && $miproductox->tipo == "estandar") {

                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $product[$i])
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();

                            $detalleinventario = Detalleinventario::find($detalle->id);
                            if ($detalleinventario) {
                                $mistock = (($detalleinventario->stockempresa) - $cantidad[$i]);
                                $detalleinventario->stockempresa = $mistock;
                                if ($detalleinventario->update()) {
                                    $inventario = Inventario::find($detalleinventario->inventario_id);
                                    $mistockt = $inventario->stocktotal - $cantidad[$i];
                                    $inventario->stocktotal = $mistockt;
                                    $inventario->update();
                                }
                            }
                        }
                    }
                }
            }

            //registrar tambien la venta
            if ($request->ingreso == "SI") {

                $empresa = DB::table('companies as c')
                    ->where('c.ruc', '=', $cliente->ruc)
                    ->select('c.id', 'c.ruc', 'c.nombre')
                    ->first();
                $client = DB::table('clientes as c')
                    ->where('c.ruc', '=', $company->ruc)
                    ->select('c.id', 'c.ruc', 'c.nombre')
                    ->first();
                $ingreso = new Ingreso;
                $ingreso->company_id = $empresa->id;
                $ingreso->cliente_id = $client->id;
                $ingreso->fecha = $fecha;
                $ingreso->costoventa = $costoventa;
                $ingreso->formapago = $formapago;
                $ingreso->moneda = $moneda;
                $ingreso->factura = $request->factura;
                $ingreso->pagada = $pagada;
                //no obligatorios

                $ingreso->observacion = $observacion;
                if ($formapago == 'credito') {
                    $ingreso->fechav = $fechav;
                }

                $ingreso->tasacambio = $tasacambio;

                //guardamos la venta y los detalles
                if ($ingreso->save()) {
                    $this->crearhistorial('crear', $venta->id, $empresa->nombre, $client->nombre, 'ingresos');
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
                            $Detalleingreso->servicio = $servicio[$i];
                            $Detalleingreso->preciofinal = $preciofinal[$i];
                            if ($Detalleingreso->save()) {

                                $productb = Product::find($product[$i]);
                                $micliente = DB::table('clientes as c')
                                    ->where('c.id', '=', $cliente->id)
                                    ->select('c.id', 'c.ruc')
                                    ->first();
                                $miempresa = DB::table('companies as c')
                                    ->where('c.ruc', '=', $micliente->ruc)
                                    ->select('c.id', 'c.ruc')
                                    ->first();

                                //pacar cuanto el producto es un kit
                                if ($productb && $productb->tipo == "kit") {
                                    $milistaproductos = $this->productosxkit($product[$i]);
                                    for ($j = 0; $j < count($milistaproductos); $j++) {
                                        $detalle = DB::table('detalleinventarios as di')
                                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                            ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                            ->where('di.company_id', '=', $miempresa->id)
                                            ->select('di.id')
                                            ->first();
                                        if (!$detalle) {
                                            $inv3 = DB::table('inventarios as i')
                                                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                                ->select('i.id')
                                                ->first();
                                            if ($inv3) {
                                                $detalle2 = new Detalleinventario;
                                                $detalle2->company_id = $miempresa->id;
                                                $detalle2->inventario_id = $inv3->id;
                                                $detalle2->stockempresa = 0;
                                                $detalle2->status = 0;
                                                $detalle2->save();
                                                $detalle = $detalle2;
                                            }
                                        }
                                        //corregir el error al guardar un producto de un kit sin stock
                                        $detalleinventario = Detalleinventario::find($detalle->id);
                                        if ($detalleinventario) {
                                            $mistock = (($detalleinventario->stockempresa) + (($milistaproductos[$j]->cantidad) * $cantidad[$i]));
                                            $detalleinventario->stockempresa = $mistock;
                                            if ($detalleinventario->update()) {
                                                $inventario = Inventario::find($detalleinventario->inventario_id);
                                                $mistockt = $inventario->stocktotal +  (($milistaproductos[$j]->cantidad) * $cantidad[$i]);
                                                $inventario->stocktotal = $mistockt;
                                                $inventario->update();
                                            }
                                        }
                                    }
                                }
                                //para cuando el producto es estandar
                                else if ($productb && $productb->tipo == "estandar") {
                                    $detalle = DB::table('detalleinventarios as di')
                                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                        ->where('i.product_id', '=', $product[$i])
                                        ->where('di.company_id', '=', $miempresa->id)
                                        ->select('di.id')
                                        ->first();
                                    if ($detalle == null) {
                                        $inv3 = DB::table('inventarios as i')
                                            ->where('i.product_id', '=', $product[$i])
                                            ->select('i.id')
                                            ->first();
                                        //$inventario = Inventario::find($inv3->id);
                                        $detalle2 = new Detalleinventario;
                                        $detalle2->company_id = $miempresa->id;
                                        $detalle2->inventario_id = $inv3->id;
                                        $detalle2->stockempresa = 0;
                                        $detalle2->status = 0;
                                        $detalle2->save();
                                        $detalle = $detalle2;
                                    }
                                    $detalleinventario = Detalleinventario::find($detalle->id);
                                    if ($detalleinventario) {
                                        $mistock = (($detalleinventario->stockempresa) + $cantidad[$i]);
                                        $detalleinventario->stockempresa = $mistock;
                                        if ($detalleinventario->update()) {
                                            $inventario = Inventario::find($detalleinventario->inventario_id);
                                            $mistockt = $inventario->stocktotal + $cantidad[$i];
                                            $inventario->stocktotal = $mistockt;
                                            $inventario->update();
                                        }
                                    }
                                }
                                //para actualizar el precio maximo y minimo
                                if ($productb) {
                                    if ($moneda == $productb->moneda) {
                                        if ($preciounitariomo[$i] > $productb->NoIGV) {
                                            $productb->maximo = $preciounitariomo[$i];
                                        } else  if ($preciounitariomo[$i] < $productb->NoIGV) {
                                            $productb->minimo = $preciounitariomo[$i];
                                        }
                                    } else if ($moneda == "dolares" && $productb->moneda == "soles") {
                                        if ($preciounitariomo[$i] > round(($productb->NoIGV) / $tasacambio, 2)) {
                                            $productb->maximo = round($preciounitariomo[$i] * $tasacambio, 2);
                                        } else  if ($preciounitariomo[$i] < round(($productb->NoIGV) / $tasacambio, 2)) {
                                            $productb->minimo = round($preciounitariomo[$i] * $tasacambio, 2);
                                        }
                                    } else if ($moneda == "soles" && $productb->moneda == "dolares") {
                                        if ($preciounitariomo[$i] > round(($productb->NoIGV) * $tasacambio, 2)) {
                                            $productb->maximo = round($preciounitariomo[$i] / $tasacambio, 2);
                                        } else  if ($preciounitariomo[$i] < round(($productb->NoIGV) * $tasacambio, 2)) {
                                            $productb->minimo = round($preciounitariomo[$i] / $tasacambio, 2);
                                        }
                                    }
                                    $productb->save();
                                }
                                //fin del guardar detalle
                            }
                        }
                    }
                }
            }
            //termino de registrar la venta
            $this->crearhistorial('crear', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
            return redirect('admin/venta')->with('message', 'Venta Agregada Satisfactoriamente');
        }
        return redirect('admin/venta')->with('message', 'No se Pudo Agregar la Venta');
    }
    public function create2(int $idcotizacion)
    {
        $venta = Cotizacion::findOrFail($idcotizacion);
        $companies = DB::table('companies as c')
            ->join('cotizacions as cot', 'cot.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('cot.id', '=', $idcotizacion)
            ->get();
        $clientes = DB::table('clientes as c')
            ->join('cotizacions as cot', 'cot.cliente_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('cot.id', '=', $idcotizacion)
            ->get();

        $detallesventa = DB::table('detallecotizacions as dc')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->join('products as p', 'dc.product_id', '=', 'p.id')
            ->select(
                'dc.observacionproducto',
                'p.tipo',
                'p.moneda',
                'dc.id as iddetalleventa',
                'dc.cantidad',
                'dc.preciounitario',
                'dc.preciounitariomo',
                'dc.servicio',
                'dc.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('c.id', '=', $idcotizacion)->get();

        //return $detallesventa;
        $prodstock = collect();
        for ($i = 0; $i < count($detallesventa); $i++) {
            if ($detallesventa[$i]->tipo == "estandar") {
                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $venta->company_id)
                    ->where('i.product_id', '=', $detallesventa[$i]->idproducto)
                    ->select('di.stockempresa', 'di.company_id', 'i.product_id')
                    ->first();
                //dump($inventario);  //dump para imprimir en la terminal sin detener el proceso
                $stock = collect();
                $stock->put('idproducto', $detallesventa[$i]->idproducto);
                $stock->put('producto', $detallesventa[$i]->producto);
                $stock->put('stockdisponible', $inventario->stockempresa);
                $stock->put('stockrequerido', $detallesventa[$i]->cantidad);
                $prodstock->push($stock);
            } else if ($detallesventa[$i]->tipo == "kit") {
                $misproductos = $this->productosxkit($detallesventa[$i]->idproducto);

                //return $misproductos;
                for ($j = 0; $j < count($misproductos); $j++) {
                    $inventario = DB::table('inventarios as i')
                        ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                        ->join('companies as c', 'di.company_id', '=', 'c.id')
                        ->where('c.id', '=', $venta->company_id)
                        ->where('i.product_id', '=', $misproductos[$j]->id)
                        ->select('di.stockempresa', 'di.company_id', 'i.product_id')
                        ->first();
                    $stock = collect();
                    $stock->put('idproducto', $misproductos[$j]->id);
                    $stock->put('producto', $misproductos[$j]->producto);
                    $stock->put('stockdisponible', $inventario->stockempresa);
                    $stock->put('stockrequerido', ($detallesventa[$i]->cantidad * $misproductos[$j]->cantidad));
                    $prodstock->push($stock);
                }
            }
        }
        //dump($prodstock);
        //return $prodstock;
        $prodfaltantes = collect();
        $milista2 = $prodstock->unique('idproducto');
        $milistaprod = $milista2->values()->all();
        //return $milistaprod;
        for ($z = 0; $z < count($milistaprod); $z++) {
            $mistockrequerido = 0;
            for ($x = 0; $x < count($prodstock); $x++) {
                //return($prodstock[$x]["idproducto"]);
                if ($milistaprod[$z]["idproducto"] == $prodstock[$x]["idproducto"]) {
                    $mistockrequerido = $mistockrequerido + $prodstock[$x]["stockrequerido"];
                }
            }
            if ($mistockrequerido > $milistaprod[$z]["stockdisponible"]) {
                $miprod = collect();
                $miprod->put('idproducto', $milistaprod[$z]["idproducto"]);
                $miprod->put('producto', $milistaprod[$z]["producto"]);
                $miprod->put('stockdisponible', $milistaprod[$z]["stockdisponible"]);
                $miprod->put('stockrequerido', ($mistockrequerido - $milistaprod[$z]["stockdisponible"]));
                $prodfaltantes->push($miprod);
            }
        }

        //return $prodfaltantes;

        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pc', 'k.product_id', '=', 'pc.id')
            ->join('detallecotizacions as dc', 'dc.product_id', '=', 'pc.id')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('c.id', '=', $idcotizacion)->get();



        //return $detalleskit;
        return view('admin.venta.createventacotizacion', compact('prodfaltantes', 'venta', 'companies', 'clientes', 'detallesventa', 'detalleskit'));
    }

    public function edit(int $venta_id)
    {
        $venta = Venta::findOrFail($venta_id);
        //$companies = Company::all();
        $companies = DB::table('companies as c')
            ->join('ventas as v', 'v.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('v.id', '=', $venta_id)
            ->get();
        $clientes = Cliente::all();



        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select('dv.observacionproducto', 'p.tipo', 'p.moneda', 'dv.id as iddetalleventa', 'dv.cantidad', 'dv.preciounitario', 'dv.preciounitariomo', 'dv.servicio', 'dv.preciofinal', 'p.id as idproducto', 'p.nombre as producto')
            ->where('v.id', '=', $venta_id)->get();

        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('detalleventas as dv', 'dv.product_id', '=', 'pv.id')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('v.id', '=', $venta_id)->get();
        //return $detalleskit;
        //return $detallesventa;
        return view('admin.venta.edit', compact('venta', 'companies', 'clientes', 'detallesventa', 'detalleskit'));
    }

    public function update(VentaFormRequest $request, int $venta_id)
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
        if ($formapago == 'credito') {
            $venta->fechav = $fechav;
        } elseif ($formapago == 'contado') {
            $venta->fechav = null;
        }
        //guardamos la venta y los detalles
        if ($venta->update()) {
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
                    $Detalleventa->servicio = $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if ($Detalleventa->save()) {

                        $miproductox = Product::find($product[$i]);
                        if ($miproductox && $miproductox->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();

                                $detalleinventario = Detalleinventario::find($detalle->id);
                                if ($detalleinventario) {
                                    $mistock = (($detalleinventario->stockempresa) - (($milistaproductos[$j]->cantidad) * $cantidad[$i]));
                                    $detalleinventario->stockempresa = $mistock;
                                    if ($detalleinventario->update()) {
                                        $inventario = Inventario::find($detalleinventario->inventario_id);
                                        $mistockt = $inventario->stocktotal - (($milistaproductos[$j]->cantidad) * $cantidad[$i]);
                                        $inventario->stocktotal = $mistockt;
                                        $inventario->update();
                                    }
                                }
                            }
                        } else if ($miproductox && $miproductox->tipo == "estandar") {

                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $product[$i])
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();

                            $detalleinventario = Detalleinventario::find($detalle->id);
                            if ($detalleinventario) {
                                $mistock = (($detalleinventario->stockempresa) - $cantidad[$i]);
                                $detalleinventario->stockempresa = $mistock;
                                if ($detalleinventario->update()) {
                                    $inventario = Inventario::find($detalleinventario->inventario_id);
                                    $mistockt = $inventario->stocktotal - $cantidad[$i];
                                    $inventario->stocktotal = $mistockt;
                                    $inventario->update();
                                }
                            }
                        }
                    }
                }
                //return redirect('admin/venta')->with('message', 'Venta Actualizada Satisfactoriamente');
            }
            $this->crearhistorial('editar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
            return redirect('admin/venta')->with('message', 'Venta Actualizada Satisfactoriamente');
        } else {
            return redirect('admin/venta')->with('message', 'Venta NO Actualizada');
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
                'p.id as idproducto',
                'p.tipo',
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

    public function showcreditos()
    {
        $fechahoy = date('Y-m-d');
        $fechalimite =  date("Y-m-d", strtotime($fechahoy . "+ 7 days"));

        $creditosvencidos = DB::table('ventas as v')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->where('v.fechav', '!=', null)
            ->where('v.fechav', '<=', $fechalimite)
            ->where('v.pagada', '=', 'NO')
            ->select(
                'v.id',
                'v.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'v.moneda',
                'v.costoventa',
                'v.pagada',
                'v.fechav',
                'v.factura',
                'v.formapago'
            )
            ->get();
        //$nrocreditosvencidos =count($creditosvencidos);

        return $creditosvencidos;
    }

    public function destroy(int $venta_id)
    {
        $venta = Venta::find($venta_id);
        if ($venta) {
            $detallesventa = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->select('dv.cantidad', 'dv.product_id', 'p.tipo', 'p.id')
                ->where('v.id', '=', $venta_id)->get();
            for ($i = 0; $i < count($detallesventa); $i++) {

                if ($detallesventa[$i]->tipo == "estandar") {
                    $detallesinventario = DB::table('detalleinventarios as di')
                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                        ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                        ->where('i.product_id', '=', $detallesventa[$i]->product_id)
                        ->where('di.company_id', '=', $venta->company_id)
                        ->first();

                    //aca genera error porque no encuentra el inventario del kit

                    $detalleinv = Detalleinventario::find($detallesinventario->id);
                    $inventario = Inventario::find($detallesinventario->inventario_id);

                    if ($detalleinv) {
                        $detalleinv->stockempresa = $detalleinv->stockempresa + $detallesventa[$i]->cantidad;
                        if ($detalleinv->update()) {
                            $inventario->stocktotal = $inventario->stocktotal + $detallesventa[$i]->cantidad;
                            $inventario->update();
                        }
                    }
                } else if ($detallesventa[$i]->tipo == "kit") {
                    $products = $this->productosxkit($detallesventa[$i]->id);
                    for ($x = 0; $x < count($products); $x++) {
                        $detallesinventario = DB::table('detalleinventarios as di')
                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                            ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                            ->where('i.product_id', '=', $products[$x]->id)
                            ->where('di.company_id', '=', $venta->company_id)
                            ->first();

                        $detalleinv = Detalleinventario::find($detallesinventario->id);
                        $inventario = Inventario::find($detallesinventario->inventario_id);

                        if ($detalleinv) {
                            $detalleinv->stockempresa = $detalleinv->stockempresa + ($detallesventa[$i]->cantidad * $products[$x]->cantidad);
                            if ($detalleinv->update()) {
                                $inventario->stocktotal = $inventario->stocktotal + ($detallesventa[$i]->cantidad * $products[$x]->cantidad);
                                $inventario->update();
                            }
                        }
                    }
                }
            }
            if ($venta->delete()) {
                $company = Company::find($venta->company_id);
                $cliente = Cliente::find($venta->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    public function destroydetalleventa($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleventa = Detalleventa::find($id);
        if ($detalleventa) {
            $midetalle = $detalleventa;
            $venta = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->select('dv.cantidad', 'v.costoventa', 'dv.preciofinal', 'v.id', 'v.company_id as idempresa', 'dv.product_id as idproducto')
                ->where('dv.id', '=', $id)->first();
            if ($detalleventa->delete()) {
                $costof = $venta->costoventa;
                $detalle = $venta->preciofinal;
                $idventa = $venta->id;

                $ventaedit = Venta::findOrFail($idventa);
                $ventaedit->costoventa = $costof - $detalle;
                if ($ventaedit->update()) {

                    //buscamos el producto para actualizar los stocks
                    $product = Product::find($midetalle->product_id);
                    if ($product->tipo == "kit") {
                        $milistaproductos = $this->productosxkit($product->id);
                        for ($j = 0; $j < count($milistaproductos); $j++) {
                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                ->where('di.company_id', '=', $venta->idempresa)
                                ->select('di.id')
                                ->first();

                            $detalleinventario = Detalleinventario::find($detalle->id);
                            if ($detalleinventario) {
                                $mistock = (($detalleinventario->stockempresa) + (($milistaproductos[$j]->cantidad) * $midetalle->cantidad));
                                $detalleinventario->stockempresa = $mistock;
                                if ($detalleinventario->update()) {
                                    $inventario = Inventario::find($detalleinventario->inventario_id);
                                    $mistockt = $inventario->stocktotal +  (($milistaproductos[$j]->cantidad) * $midetalle->cantidad);
                                    $inventario->stocktotal = $mistockt;
                                    $inventario->update();
                                }
                            }
                        }
                    } else if ($product->tipo == "estandar") {
                        $detalleInv = DB::table('detalleinventarios as di')
                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                            ->where('i.product_id', '=', $venta->idproducto)
                            ->where('di.company_id', '=', $venta->idempresa)
                            ->select('di.id', 'i.stocktotal')
                            ->first();
                        $detalleinventario = Detalleinventario::findOrFail($detalleInv->id);
                        if ($detalleinventario) {
                            $mistock2 = $detalleinventario->stockempresa + $venta->cantidad;
                            $detalleinventario->stockempresa = $mistock2;
                            if ($detalleinventario->update()) {
                                $inventario = Inventario::find($detalleinventario->inventario_id);
                                $mistockt = $inventario->stocktotal + $venta->cantidad;
                                $inventario->stocktotal = $mistockt;
                                $inventario->update();
                            }
                        }
                    }
                }
                $company = Company::find($venta->company_id);
                $cliente = Cliente::find($venta->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
                return "1";
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function pagarfactura($id)
    {
        //buscamos el registro con el id enviado por la URL
        $venta = Venta::find($id);
        if ($venta) {
            $venta->pagada = "SI";
            if ($venta->update()) {
                $company = Company::find($venta->company_id);
                $cliente = Cliente::find($venta->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
                return "1";
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function productosxempresa($id)
    {
        $prod = DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nombre',
                'p.NoIGV',
                'p.moneda',
                'p.tipo',
                'di.stockempresa',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3'
            )
            ->where('c.id', '=', $id)
            ->where('p.status', '=', 0)
            ->where('p.tipo', '=', "estandar")
            ->where('di.stockempresa', '>', 0)->get();

        $kits = DB::table('products as p')
            ->where('p.status', '=', 0)
            ->where('p.tipo', '=', "kit")
            ->select(
                'p.id',
                'p.nombre',
                'p.NoIGV',
                'p.moneda',
                'p.tipo',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3'
            )
            ->get();


        $miskits = collect();
        for ($i = 0; $i < count($kits); $i++) {
            $stockmin = 100000;
            $stockkit = 100000;
            $existeinvetario = 1;
            $listakits = DB::table('products as p')
                ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
                ->where('k.product_id', '=', $kits[$i]->id)
                ->select(
                    'p.id as idkitproduct',
                    'p.nombre',
                    'p.NoIGV',
                    'p.moneda',
                    'p.tipo',
                    'k.id as idkit',
                    'k.cantidad',
                    'k.preciounitario',
                    'k.preciounitariomo',
                    'k.preciofinal'
                )
                ->get();
            for ($j = 0; $j < count($listakits); $j++) {

                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('i.product_id', '=', $listakits[$j]->idkitproduct)
                    ->where('di.company_id', '=', $id)
                    ->select('i.id as idinventario', 'di.stockempresa')
                    ->first();
                if ($inventario != null  &&  (floor($inventario->stockempresa / $listakits[$j]->cantidad) != 0)) {
                    $stockkit = floor($inventario->stockempresa / $listakits[$j]->cantidad);
                    if ($stockkit < $stockmin) {
                        $stockmin = $stockkit;
                    }
                } else {
                    $existeinvetario = 0;
                }
            }

            if ($stockmin != 100000 &&  $existeinvetario == 1) {
                $mikit = collect();
                $mikit->put('id', $kits[$i]->id);
                $mikit->put('nombre', $kits[$i]->nombre);
                $mikit->put('NoIGV', $kits[$i]->NoIGV);
                $mikit->put('moneda', $kits[$i]->moneda);
                $mikit->put('tipo', $kits[$i]->tipo);
                $mikit->put('stockempresa', $stockmin);
                $mikit->put('cantidad2', null);
                $mikit->put('precio2', null);
                $mikit->put('cantidad3', null);
                $mikit->put('precio3', null);
                $miskits->push($mikit);
            }
        }

        $products = $prod->concat($miskits);
        return $products;
    }

    public function productosxkit($kit_id)
    {

        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad')
            ->get();

        return  $productosxkit;
    }

    public function comboempresacliente($id)
    {
        //buscamos el registro con el id enviado por la URL
        $empresa = Company::find($id);

        $products = DB::table('clientes as c')
            ->select('c.id', 'c.nombre')
            ->where('c.ruc', '!=', $empresa->ruc)
            ->where('c.status', '=', '0')
            ->get();

        return $products;
    }

    public function generarfacturapdf($id)
    {
        $vent = Venta::find($id);
        $empresa = Company::find($vent->company_id);
        $cliente = Cliente::find($vent->cliente_id);
        $venta = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
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
                'v.factura',
                'v.costoventa',
                'v.tasacambio',
                'v.costoventa',
                'p.tipo',
                'dv.id as iddetalle'
            )
            ->where('v.id', '=', $id)->get();
        $detallekit = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select(
                'v.id as idventa',
                'k.cantidad',
                'pk.nombre',
                'dv.id as iddetalle'
            )
            ->where('v.id', '=', $id)->get();
        //return $venta;
        $pdf = PDF::loadView(
            'admin.venta.facturapdf',
            ["venta" => $venta, "empresa" => $empresa, "cliente" => $cliente, "detallekit" => $detallekit]
        );
        return $pdf->stream('venta.pdf');
    }

    public function stockkitxempresa($idkit)
    {
        $companies = Company::all();
        $milistaproductos = $this->productosxkit($idkit);
        $mistockkits = collect();
        for ($i = 0; $i < count($companies); $i++) {
            $existeinventario = 1;
            $stockminimo = 100000;
            for ($j = 0; $j < count($milistaproductos); $j++) {
                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                    ->where('di.company_id', '=', $companies[$i]->id)
                    ->select('i.id as idinventario', 'di.stockempresa')
                    ->first();
                if ($inventario != null  &&  (floor($inventario->stockempresa / $milistaproductos[$j]->cantidad) != 0)) {
                    $stockkit = floor($inventario->stockempresa / $milistaproductos[$j]->cantidad);
                    if ($stockkit < $stockminimo) {
                        $stockminimo = $stockkit;
                    }
                } else {
                    $existeinventario = 0;
                }
            }
            if ($stockminimo != 100000 &&  $existeinventario == 1) {
                $mistocke = collect();
                $mistocke->put('id', $companies[$i]->id);
                $mistocke->put('empresa', $companies[$i]->nombre);
                $mistocke->put('stock', $stockminimo);
                $mistockkits->push($mistocke);
            }
        }
        return  $mistockkits;
    }

    public function stockxprodxempresa($idkit, $idempresa)
    {
        $stockprod = collect();
        $milistaproductos = $this->productosxkit($idkit);
        for ($j = 0; $j < count($milistaproductos); $j++) {

            $inventario = DB::table('inventarios as i')
                ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->where('p.id', '=', $milistaproductos[$j]->id)
                ->where('di.company_id', '=', $idempresa)
                ->select('p.id', 'p.nombre', 'di.stockempresa')
                ->first();

            if ($inventario) {
                $stockprod->push($inventario);
            }
        }

        return  $stockprod;
    }

    public function comboempresaclientevi($id)
    {
        //buscamos el registro con el id enviado por la URL
        $empresa = Company::find($id);

        $clientes = DB::table('clientes as c')
            ->join('companies as com', 'com.ruc', '=', 'c.ruc')
            ->select('c.id', 'c.nombre')
            ->where('c.ruc', '!=', $empresa->ruc)
            ->where('c.status', '=', '0')
            ->get();

        return $clientes;
    }

    public function facturadisponible($empresa, $factura)
    {

        $ventas = DB::table('ventas as v')
            ->where('v.company_id', '=', $empresa)
            ->where('v.factura', '=', $factura)
            ->select('v.id')
            ->get();
        if (count($ventas) > 0) {
            return "NO";
        } else {
            return "SI";
        }
    }

    public function misdetallesventa($venta_id)
    {
        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select('dv.observacionproducto', 'p.tipo', 'p.moneda', 'dv.id as iddetalleventa', 'dv.cantidad', 'dv.preciounitario', 'dv.preciounitariomo', 'dv.servicio', 'dv.preciofinal', 'p.id as idproducto', 'p.nombre as producto')
            ->where('v.id', '=', $venta_id)->get();

        return  $detallesventa;
    }

    public function stocktotalxkit($idkit)
    {

        $milistaproductos = $this->productosxkit($idkit);
        $stockkit = 100000;
        for ($j = 0; $j < count($milistaproductos); $j++) {
            $inventario = DB::table('inventarios as i')
                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                ->select('i.id', 'i.stocktotal', 'i.product_id')
                ->first();
            $stockprod = 0;
            if ($inventario) {
                $stockprod = floor($inventario->stocktotal / $milistaproductos[$j]->cantidad);
            }
            if ($stockprod < $stockkit) {
                $stockkit = $stockprod;
            }
        }
        return  $stockkit;
    }
}
