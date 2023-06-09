<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use App\Models\Company;
use App\Models\Ingreso;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Detalleingreso;
use App\Models\Detalleinventario;
use App\Models\Inventario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IngresoFormRequest;
use Yajra\DataTables\DataTables;

class IngresoController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-ingreso|editar-ingreso|crear-ingreso|eliminar-ingreso',
            ['only' => ['index', 'show', 'showcreditos', 'pagarfactura', 'productosxkit']]
        );
        $this->middleware('permission:crear-ingreso', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-ingreso', ['only' => ['edit', 'update', 'destroydetalleingreso']]);
        $this->middleware('permission:eliminar-ingreso', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        $fechahoy = date('Y-m-d');
        $fechalimite =  date("Y-m-d", strtotime($fechahoy . "+ 7 days"));

        $creditosxvencer = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
            ->where('i.fechav', '<=', $fechalimite)
            ->where('i.pagada', '=', 'NO')
            ->select(
                'i.id',
                'i.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'i.moneda',
                'i.costoventa',
                'i.pagada',
                'i.fechav',
                'i.factura',
                'i.formapago'
            )
            ->count();

        $sinnumero = DB::table('ingresos as i')
            ->where('i.factura', '=', null)
            ->select('i.id')
            ->count();

        if ($request->ajax()) {

            $ingresos = DB::table('ingresos as i')
                ->join('clientes as c', 'i.cliente_id', '=', 'c.id')
                ->join('companies as e', 'i.company_id', '=', 'e.id')
                ->select(
                    'i.id',
                    'c.nombre as cliente',
                    'e.nombre as empresa',
                    'i.moneda',
                    'i.formapago',
                    'i.factura',
                    'i.costoventa',
                    'i.pagada',
                    'i.fecha',
                );
            return DataTables::of($ingresos)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ingresos) {
                    return view('admin.ingreso.botones', compact('ingresos'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.ingreso.index', compact('creditosxvencer', 'sinnumero'));
    }
    public function index2(){
        return redirect('admin/ingreso')->with('verstock', 'Ver');
    }
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = DB::table('products as p')
            ->select('p.id', 'p.nombre', 'p.NoIGV', 'p.moneda', 'p.tipo', 'p.NoIGV')
            ->where('p.status', '=', 0)
            //->where('p.tipo', '=', "estandar")
            //->where('di.stockempresa', '>', 0)
            ->get();

        return view('admin.ingreso.create', compact('companies', 'products', 'clientes'));
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
        //$factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];

        $ingreso = new Ingreso;
        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;
        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];

        $ingreso->observacion = $observacion;
        if ($formapago == 'credito') {
            $ingreso->fechav = $fechav;
        }

        $ingreso->tasacambio = $tasacambio;

        //guardamos la venta y los detalles
        if ($ingreso->save()) {
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
                        //pacar cuanto el producto es un kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();
                                if (!$detalle) {
                                    $inv3 = DB::table('inventarios as i')
                                        ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                        ->select('i.id')
                                        ->first();
                                    if ($inv3) {
                                        $detalle2 = new Detalleinventario;
                                        $detalle2->company_id = $company->id;
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
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();
                            if ($detalle == null) {
                                $inv3 = DB::table('inventarios as i')
                                    ->where('i.product_id', '=', $product[$i])
                                    ->select('i.id')
                                    ->first();
                                //$inventario = Inventario::find($inv3->id);
                                $detalle2 = new Detalleinventario;
                                $detalle2->company_id = $company->id;
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
            return redirect('admin/ingreso')->with('message', 'Ingreso Agregado Satisfactoriamente');
        }
        return redirect('admin/ingreso')->with('message', 'No se Pudo Agregar el Ingreso');
    }
    public function update(IngresoFormRequest $request, int $ingreso_id)
    {
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);

        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        //$factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];

        $ingreso =  Ingreso::findOrFail($ingreso_id);

        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;


        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];

        $ingreso->observacion = $observacion;
        if ($formapago == 'credito') {
            $ingreso->fechav = $fechav;
        } elseif ($formapago == 'contado') {
            $ingreso->fechav = null;
        }
        $ingreso->tasacambio = $tasacambio;

        //guardamos la venta y los detalles
        if ($ingreso->update()) {
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
                    $Detalleingreso->servicio = $servicio[$i];
                    $Detalleingreso->preciofinal = $preciofinal[$i];
                    if ($Detalleingreso->save()) {

                        $productb = Product::find($product[$i]);
                        //pacar cuanto el producto es un kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();
                                if (!$detalle) {
                                    $inv3 = DB::table('inventarios as i')
                                        ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                        ->select('i.id')
                                        ->first();
                                    if ($inv3) {
                                        $detalle2 = new Detalleinventario;
                                        $detalle2->company_id = $company->id;
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
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();
                            if ($detalle == null) {
                                $inv3 = DB::table('inventarios as i')
                                    ->where('i.product_id', '=', $product[$i])
                                    ->select('i.id')
                                    ->first();
                                //$inventario = Inventario::find($inv3->id);
                                $detalle2 = new Detalleinventario;
                                $detalle2->company_id = $company->id;
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
                return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
            }

            return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
        }
    }
    public function edit(int $ingreso_id)
    {
        //$companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all()->where('status','=',0);
        $companies = DB::table('companies as c')
            ->join('ingresos as i', 'i.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('i.id', '=', $ingreso_id)
            ->get();


        $ingreso = Ingreso::findOrFail($ingreso_id);
        $detallesingreso = DB::table('detalleingresos as di')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->select('di.observacionproducto', 'p.tipo', 'p.moneda', 'di.id as iddetalleingreso', 'di.cantidad', 
            'di.preciounitario', 'di.preciounitariomo', 'di.servicio', 'di.preciofinal', 'p.id as idproducto', 
            'p.nombre as producto')
            ->where('i.id', '=', $ingreso_id)->get();
        //return $detallesventa;
        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('detalleingresos as di', 'di.product_id', '=', 'pv.id')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('i.id', '=', $ingreso_id)->get();

        return view('admin.ingreso.edit', compact('products', 'ingreso', 'companies', 'clientes', 'detalleskit', 'detallesingreso'));
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
                'i.pagada',
                'p.tipo',
                'p.id as idproducto',

            )
            ->where('i.id', '=', $id)->get();

        return  $ingreso;
    }
    public function destroy(int $ingreso_id)
    {
        $ingreso = Ingreso::find($ingreso_id);
        if ($ingreso) {
            $detallesingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id') 
                ->join('products as p', 'di.product_id', '=', 'p.id')
                ->select('di.cantidad', 'di.product_id', 'p.tipo', 'p.id')
                ->where('i.id', '=', $ingreso_id)->get();
            for ($i = 0; $i < count($detallesingreso); $i++) {

                if ($detallesingreso[$i]->tipo == "estandar") {
                    $detallesinventario = DB::table('detalleinventarios as di')
                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                        ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                        //->where('i.id', '=', $venta_id)
                        ->where('i.product_id', '=', $detallesingreso[$i]->product_id)
                        ->where('di.company_id', '=', $ingreso->company_id)
                        ->first();

                    $detalleinv = Detalleinventario::find($detallesinventario->id);
                    $inventario = Inventario::find($detallesinventario->inventario_id);

                    if ($detalleinv) {
                        $detalleinv->stockempresa = $detalleinv->stockempresa - $detallesingreso[$i]->cantidad;
                        if ($detalleinv->update()) {
                            $inventario->stocktotal = $inventario->stocktotal - $detallesingreso[$i]->cantidad;
                            $inventario->update();
                        }
                    }
                } else if ($detallesingreso[$i]->tipo == "kit") {
                    $products = $this->productosxkit($detallesingreso[$i]->id);
                    for ($x = 0; $x < count($products); $x++) {
                        $detallesinventario = DB::table('detalleinventarios as di')
                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                            ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                            ->where('i.product_id', '=', $products[$x]->id)
                            ->where('di.company_id', '=', $ingreso->company_id)
                            ->first();

                        $detalleinv = Detalleinventario::find($detallesinventario->id);
                        $inventario = Inventario::find($detallesinventario->inventario_id);

                        if ($detalleinv) {
                            $detalleinv->stockempresa = $detalleinv->stockempresa - ($detallesingreso[$i]->cantidad * $products[$x]->cantidad);
                            if ($detalleinv->update()) {
                                $inventario->stocktotal = $inventario->stocktotal - ($detallesingreso[$i]->cantidad * $products[$x]->cantidad);
                                $inventario->update();
                            }
                        }
                    }
                }
            }
            if ($ingreso->delete()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    public function showcreditos()
    {
        $fechahoy = date('Y-m-d');
        $fechalimite =  date("Y-m-d", strtotime($fechahoy . "+ 7 days"));

        $creditosvencidos = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
            ->where('i.fechav', '<=', $fechalimite)
            ->where('i.pagada', '=', 'NO')
            ->select(
                'i.id',
                'i.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'i.moneda',
                'i.costoventa',
                'i.pagada',
                'i.fechav',
                'i.factura',
                'i.formapago'
            )
            ->get();
        //$nrocreditosvencidos =count($creditosvencidos);

        return $creditosvencidos;
    }
    public function pagarfactura($id)
    {
        //buscamos el registro con el id enviado por la URL
        $ingreso = Ingreso::find($id);
        if ($ingreso) {
            $ingreso->pagada = "SI";
            if ($ingreso->update()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    public function destroydetalleingreso($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleingreso = Detalleingreso::find($id);
        if ($detalleingreso) {
            $midetalle = $detalleingreso;
            $ingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                ->select('di.cantidad', 'i.costoventa', 'di.preciofinal', 'i.id', 'di.product_id as idproducto', 'i.company_id as idempresa')
                ->where('di.id', '=', $id)->first();
            if ($detalleingreso->delete()) {
                $costof = $ingreso->costoventa;
                $detalle = $ingreso->preciofinal;
                $idingreso = $ingreso->id;

                $ingresoedit = Ingreso::findOrFail($idingreso);
                $ingresoedit->costoventa = $costof - $detalle;
                if ($ingresoedit->update()) {
                    //buscamos el producto para actualizar los stocks
                    $product = Product::find($midetalle->product_id);
                    if ($product->tipo == "kit") {
                        $milistaproductos = $this->productosxkit($product->id);
                        for ($j = 0; $j < count($milistaproductos); $j++) {
                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                ->where('di.company_id', '=', $ingreso->idempresa)
                                ->select('di.id')
                                ->first();

                            $detalleinventario = Detalleinventario::find($detalle->id);
                            if ($detalleinventario) {
                                $mistock = (($detalleinventario->stockempresa) - (($milistaproductos[$j]->cantidad) * $midetalle->cantidad));
                                $detalleinventario->stockempresa = $mistock;
                                if ($detalleinventario->update()) {
                                    $inventario = Inventario::find($detalleinventario->inventario_id);
                                    $mistockt = $inventario->stocktotal -  (($milistaproductos[$j]->cantidad) * $midetalle->cantidad);
                                    $inventario->stocktotal = $mistockt;
                                    $inventario->update();
                                }
                            }
                        }
                    } else if ($product->tipo == "estandar") {

                        $detalleInv = DB::table('detalleinventarios as di')
                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                            ->where('i.product_id', '=', $ingreso->idproducto)
                            ->where('di.company_id', '=', $ingreso->idempresa)
                            ->select('di.id', 'i.stocktotal')
                            ->first();
                        $detalleinventario = Detalleinventario::findOrFail($detalleInv->id);

                        if ($detalleinventario) {
                            $mistock2 = $detalleinventario->stockempresa - $ingreso->cantidad;
                            $detalleinventario->stockempresa = $mistock2;
                            if ($detalleinventario->update()) {
                                $inventario = Inventario::find($detalleinventario->inventario_id);
                                $mistockt = $inventario->stocktotal - $ingreso->cantidad;
                                $inventario->stocktotal = $mistockt;
                                $inventario->update();
                            }
                        }
                    }
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
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
}
