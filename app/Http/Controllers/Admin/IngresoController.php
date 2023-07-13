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
use App\Traits\HistorialTrait;

class IngresoController extends Controller
{   //para asignar los permisos a las funciones
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
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
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
        return view('admin.ingreso.index');
    }
    //funcion para redirigir al index principal pero que nos muestre el modal de ingresos por pagar
    public function index2()
    {
        return redirect('admin/ingreso')->with('verstock', 'Ver');
    }
    //funcion para ver cuantos ingresos no tienen numero de factura
    public function sinnumero()
    {
        $sinnumero = DB::table('ingresos as i')
            ->where('i.factura', '=', null)
            ->select('i.id')
            ->count();
        return $sinnumero;
    }
    //funcion para ver cuantos ingresos a credito estan por vencer
    public function creditosxvencer()
    {
        $creditosxvencer = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
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
        return $creditosxvencer;
    }
    //vista crear
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = DB::table('products as p')
            ->select('p.id', 'p.nombre', 'p.NoIGV', 'p.moneda', 'p.tipo', 'p.NoIGV', 'p.unidad', 'p.preciocompra')
            ->where('p.status', '=', 0)
            ->get();
        return view('admin.ingreso.create', compact('companies', 'products', 'clientes'));
    }
    //funcion para guardar un ingreso
    public function store(IngresoFormRequest $request)
    {   //se realizan las validaciones
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $pagada = $validatedData['pagada'];
        //se crea el registro de ingreso
        $ingreso = new Ingreso;
        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $ingreso->observacion = $observacion;
        if ($formapago == 'credito') {
            $ingreso->fechav = $fechav;
        }
        $ingreso->tasacambio = $tasacambio;
        //datos del pago
        $ingreso->nrooc = $request->nrooc;
        $ingreso->guiaremision = $request->guiaremision;
        $ingreso->fechapago = $request->fechapago;
        $ingreso->acuenta1 = $request->acuenta1;
        $ingreso->acuenta2 = $request->acuenta2;
        $ingreso->acuenta3 = $request->acuenta3;
        $ingreso->saldo = $request->saldo;
        $ingreso->montopagado = $request->montopagado;
        //guardamos el ingreso y los detalles
        if ($ingreso->save()) {
            //detalles
            $product = $request->Lproduct;
            $observacionproducto = $request->Lobservacionproducto;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            $preciocompranuevo = $request->Lpreciocompranuevo;
            if ($product !== null) {
                //recorremos los detalles
                for ($i = 0; $i < count($product); $i++) {
                    //creamos y guardamos los detalles
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
                        $this->actualizarprecio($preciocompranuevo[$i], $product[$i]);
                        $productb = Product::find($product[$i]);
                        //para cuanto el producto es un kit se busca los productos de ese kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            //recorremos la lista de productos de un kit y actualizamos el stock del inventarios
                            //para cada producto
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                //obtenemos el stock de la empresa
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();
                                //si no existe un registro de stock de la empresa
                                if (!$detalle) {
                                    //buscamos el inventario del producto y le agregamos un stock de cero a la empresa
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
                                //actualizamos el stock del producto sumando la cantidad del ingreso en el stock de la empresa 
                                // y tambien actualizamos el stock total
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
                            //se obtienen el stock de la empresa
                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $product[$i])
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();
                            if ($detalle == null) {
                                //si no hay inventario para esta empresa se crea uno nuevo
                                $inv3 = DB::table('inventarios as i')
                                    ->where('i.product_id', '=', $product[$i])
                                    ->select('i.id')
                                    ->first();
                                $detalle2 = new Detalleinventario;
                                $detalle2->company_id = $company->id;
                                $detalle2->inventario_id = $inv3->id;
                                $detalle2->stockempresa = 0;
                                $detalle2->status = 0;
                                $detalle2->save();
                                $detalle = $detalle2;
                            }
                            $detalleinventario = Detalleinventario::find($detalle->id);
                            //sumamos la cantidad del ingreso al stock actual de la empresa y al stock total
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
                        //para actualizar el precio maximo y minimo del producto comprado
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
                    }
                }
            }
            $this->crearhistorial('crear', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
            return redirect('admin/ingreso')->with('message', 'Ingreso Agregado Satisfactoriamente');
        }
        return redirect('admin/ingreso')->with('message', 'No se Pudo Agregar el Ingreso');
    }
    //vista editar
    public function edit(int $ingreso_id)
    {
        //$companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all()->where('status', '=', 0);
        $companies = DB::table('companies as c')
            ->join('ingresos as i', 'i.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('i.id', '=', $ingreso_id)
            ->get();
        $ingreso = Ingreso::findOrFail($ingreso_id);
        $detallesingreso = DB::table('detalleingresos as di')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->select(
                'di.observacionproducto',
                'p.tipo',
                'p.moneda',
                'di.id as iddetalleingreso',
                'di.cantidad',
                'di.preciounitario',
                'di.preciounitariomo',
                'di.servicio',
                'di.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('i.id', '=', $ingreso_id)->get();
        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('detalleingresos as di', 'di.product_id', '=', 'pv.id')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('i.id', '=', $ingreso_id)->get();
        return view('admin.ingreso.edit', compact('products', 'ingreso', 'companies', 'clientes', 'detalleskit', 'detallesingreso'));
    }
    //funcion para mostrar un registro de un ingreso
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
                'i.nrooc',
                'i.guiaremision',
                'i.fechapago',
                'i.acuenta1',
                'i.acuenta2',
                'i.acuenta3',
                'i.saldo',
                'i.montopagado',
            )
            ->where('i.id', '=', $id)->get();
        return  $ingreso;
    }
    //funcion para actualizar un registro del ingreso
    public function update(IngresoFormRequest $request, int $ingreso_id)
    {   //valida los datos recibidos
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $pagada = $validatedData['pagada'];
        //buscamos el registro y asignamos los nuevos datos
        $ingreso =  Ingreso::findOrFail($ingreso_id);
        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;
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
        //datos del pago
        $ingreso->nrooc = $request->nrooc;
        $ingreso->guiaremision = $request->guiaremision;
        $ingreso->fechapago = $request->fechapago;
        $ingreso->acuenta1 = $request->acuenta1;
        $ingreso->acuenta2 = $request->acuenta2;
        $ingreso->acuenta3 = $request->acuenta3;
        $ingreso->saldo = $request->saldo;
        $ingreso->montopagado = $request->montopagado;
        //guardamos la venta y los detalles
        if ($ingreso->update()) {
            //los detalles del ingreso
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            $preciocompranuevo = $request->Lpreciocompranuevo;
            if ($product !== null) {
                //recorremos los detalles para guardarlos
                for ($i = 0; $i < count($product); $i++) {
                    //creamos y asignamos datos a un detalle
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
                        $this->actualizarprecio($preciocompranuevo[$i], $product[$i]);
                        $productb = Product::find($product[$i]);
                        //pacar cuanto el producto es un kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxkit($product[$i]);
                            //recorremos los productos del kit
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                //buscamos el inventario del producto y la empresa
                                $detalle = DB::table('detalleinventarios as di')
                                    ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                    ->where('di.company_id', '=', $company->id)
                                    ->select('di.id')
                                    ->first();
                                if (!$detalle) {
                                    //si no existe entonces buscamos en inventario del producto y 
                                    //creamos un detalle inventario para la empresa
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
                                //sumamos y actualizamos el stock del producto para la empresa y el stock total
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
                            //buscamos el inventario del producto y la empresa
                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $product[$i])
                                ->where('di.company_id', '=', $company->id)
                                ->select('di.id')
                                ->first();
                            if ($detalle == null) {
                                //si no existe entonces le creamos un detalle inventario a la empresa para dicho producto
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
                            //sumamos la cantidad del ingreso para actualizar el stock de la empresa y el stock total
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
                        //para actualizar el precio maximo y minimo del producto
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
                $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
            }
            return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
        }
    }
    //funcion para eliminar un registro de ingreso
    public function destroy(int $ingreso_id)
    {
        $ingreso = Ingreso::find($ingreso_id);
        if ($ingreso) {
            //obtenemos los detalles del ingreso
            $detallesingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                ->join('products as p', 'di.product_id', '=', 'p.id')
                ->select('di.cantidad', 'di.product_id', 'p.tipo', 'p.id')
                ->where('i.id', '=', $ingreso_id)->get();
            //recorremos los detalles
            for ($i = 0; $i < count($detallesingreso); $i++) {
                if ($detallesingreso[$i]->tipo == "estandar") {
                    //si el producto es estandar, buscamos su inventario en la empresa
                    $detallesinventario = DB::table('detalleinventarios as di')
                        ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                        ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                        ->where('i.product_id', '=', $detallesingreso[$i]->product_id)
                        ->where('di.company_id', '=', $ingreso->company_id)
                        ->first();
                    //buscamos el detalle inventario de la empresa e inventario total
                    $detalleinv = Detalleinventario::find($detallesinventario->id);
                    $inventario = Inventario::find($detallesinventario->inventario_id);
                    //restamos la cantidad del registro de ingreso al stock de la empresa y stock total
                    if ($detalleinv) {
                        $detalleinv->stockempresa = $detalleinv->stockempresa - $detallesingreso[$i]->cantidad;
                        if ($detalleinv->update()) {
                            $inventario->stocktotal = $inventario->stocktotal - $detallesingreso[$i]->cantidad;
                            $inventario->update();
                        }
                    }
                } else if ($detallesingreso[$i]->tipo == "kit") {
                    //si el producto es un kit entonces obtenemos los productos de ese kit
                    $products = $this->productosxkit($detallesingreso[$i]->id);
                    for ($x = 0; $x < count($products); $x++) {
                        $detallesinventario = DB::table('detalleinventarios as di')
                            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                            ->select('di.id', 'di.company_id', 'di.stockempresa', 'i.product_id', 'di.inventario_id')
                            ->where('i.product_id', '=', $products[$x]->id)
                            ->where('di.company_id', '=', $ingreso->company_id)
                            ->first();
                        //buscamos el inventario total y el inventario de la empresa
                        $detalleinv = Detalleinventario::find($detallesinventario->id);
                        $inventario = Inventario::find($detallesinventario->inventario_id);
                        //restamos la cantidad de los productos del kit ingresado del stock de la empresa y del producto 
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
            //borramos el registro del ingreso
            if ($ingreso->delete()) {
                $company = Company::find($ingreso->company_id);
                $cliente = Cliente::find($ingreso->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los ingresos a credito
    public function showcreditos()
    {
        $creditosvencidos = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
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
        return $creditosvencidos;
    }
    //funcion para pagar la factura del ingreso
    public function pagarfactura($id)
    {
        $ingreso = Ingreso::find($id);
        if ($ingreso) {
            $ingreso->pagada = "SI";
            if ($ingreso->update()) {
                $company = Company::find($ingreso->company_id);
                $cliente = Cliente::find($ingreso->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para eliminar el detalle del ingreso
    public function destroydetalleingreso($id)
    {
        $detalleingreso = Detalleingreso::find($id);
        if ($detalleingreso) {
            //buscamos el detalle que se va eliminar
            $midetalle = $detalleingreso;
            $ingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                ->select(
                    'di.cantidad',
                    'i.costoventa',
                    'di.preciofinal',
                    'i.id',
                    'di.product_id as idproducto',
                    'i.company_id as idempresa',
                    'i.cliente_id as idcliente'
                )
                ->where('di.id', '=', $id)->first();
            if ($detalleingreso->delete()) {
                //eliminamos el ingreso y actualizamos los precios
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
                        //recorremos la lista de productos del kit
                        for ($j = 0; $j < count($milistaproductos); $j++) {
                            $detalle = DB::table('detalleinventarios as di')
                                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                                ->where('di.company_id', '=', $ingreso->idempresa)
                                ->select('di.id')
                                ->first();
                            //obtenemos el inventario
                            $detalleinventario = Detalleinventario::find($detalle->id);
                            if ($detalleinventario) {
                                $mistock = (($detalleinventario->stockempresa) - (($milistaproductos[$j]->cantidad) * $midetalle->cantidad));
                                $detalleinventario->stockempresa = $mistock;
                                //actualizamos los stock del producto en el inventario
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
                        //obtenemos el inventario
                        $detalleinventario = Detalleinventario::findOrFail($detalleInv->id);
                        if ($detalleinventario) {
                            $mistock2 = $detalleinventario->stockempresa - $ingreso->cantidad;
                            $detalleinventario->stockempresa = $mistock2;
                            //actualizamos el stock de la empresa y stock total
                            if ($detalleinventario->update()) {
                                $inventario = Inventario::find($detalleinventario->inventario_id);
                                $mistockt = $inventario->stocktotal - $ingreso->cantidad;
                                $inventario->stocktotal = $mistockt;
                                $inventario->update();
                            }
                        }
                    }
                }
                $company = Company::find($ingreso->idempresa);
                $cliente = Cliente::find($ingreso->idcliente);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para obtener los productos de un kit
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad')
            ->get();
        return  $productosxkit;
    }
    //funcion para actualizar el precio de un producto al realizar una compra
    public function actualizarprecio($precio, $producto_id)
    {
        $producto = Product::find($producto_id);
        if ($producto) {
            $producto->preciocompra = $precio;
            $producto->update();
        }
    }
}
