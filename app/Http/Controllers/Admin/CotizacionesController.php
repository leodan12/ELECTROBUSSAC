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
use PDF;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class CotizacionesController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-cotizacion|editar-cotizacion|crear-cotizacion|eliminar-cotizacion',
            ['only' => ['index', 'show', 'generarcotizacionpdf', 'showcondiciones']]
        );
        $this->middleware('permission:crear-cotizacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-cotizacion', ['only' => ['edit', 'update', 'destroycondicion', 'destroydetallecotizacion']]);
        $this->middleware('permission:eliminar-cotizacion', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cotizaciones = DB::table('cotizacions as c')
                ->join('companies as e', 'c.company_id', '=', 'e.id')
                ->join('clientes as cl', 'c.cliente_id', '=', 'cl.id')
                ->select(
                    'c.id',
                    'c.fecha',
                    'e.nombre as empresa',
                    'cl.nombre as cliente',
                    'c.moneda',
                    'c.costoventasinigv',
                    'c.costoventaconigv',
                    'c.vendida',
                    'c.numero',
                    'c.formapago'
                );
            return DataTables::of($cotizaciones)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($cotizaciones) {
                    return view('admin.cotizacion.botones', compact('cotizaciones'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.cotizacion.index');
    }
    //vista crear
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.cotizacion.create', compact('companies', 'products', 'clientes'));
    }
    //funcion para guardar un registro
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

        if ($formapago == "credito") {
            $cotizacion->diascredito = $request->diascredito;
        }
        $cotizacion->tasacambio = $tasacambio;
        $cotizacion->observacion = $observacion;
        $cotizacion->fechav = $request->fechav;
        $cotizacion->persona = $request->persona;
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
            $this->crearhistorial('crear', $cotizacion->id, $company->nombre, $cliente->nombre, 'cotizaciones');
            return redirect('admin/cotizacion')->with('message', 'Cotizacion Agregada Satisfactoriamente');
        }
        return redirect('admin/cotizacion')->with('message', 'No se pudo Agregar la Cotizacion');
    }
    //vista editar
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
            ->select(
                'dc.observacionproducto',
                'p.tipo',
                'p.moneda',
                'dc.id as iddetallecotizacion',
                'dc.cantidad',
                'dc.preciounitario',
                'dc.preciounitariomo',
                'dc.servicio',
                'dc.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('c.id', '=', $cotizacion_id)->get();
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
    //funcion para actualizar un registro
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
        $cotizacion->persona = $request->persona;
        $cotizacion->moneda = $moneda;
        $cotizacion->numero = $request->numero;
        $cotizacion->vendida = "NO";
        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $formapago = $validatedData['formapago'];
        if ($formapago == "credito") {
            $cotizacion->diascredito = $request->diascredito;
        }
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
            $this->crearhistorial('editar', $cotizacion->id, $company->nombre, $cliente->nombre, 'cotizaciones');
            return redirect('admin/cotizacion')->with('message', 'Cotizacion Actualizada Satisfactoriamente');
        }
        return redirect('admin/cotizacion')->with('message', 'No se pudo Actualizar la cotizacion');
    }
    //funcion para mostrar un registro
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
                'c.diascredito',
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
                'c.persona',
                'p.tipo as tipo',
                'p.id as idproducto',
            )
            ->where('c.id', '=', $id)->get();
        return  $cotizacion;
    }
    //funcion para mostrar las condiciones dentro del modal vercondiciones
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
    //funcion para eliminar un registro
    public function destroy(int $cotizacion_id)
    {
        $cotizacion = Cotizacion::find($cotizacion_id);
        $company = Company::find($cotizacion->company_id);
        $cliente = Cliente::find($cotizacion->cliente_id);
        if ($cotizacion) {
            if ($cotizacion->delete()) {
                $this->crearhistorial('eliminar', $cotizacion->id, $company->nombre, $cliente->nombre, 'cotizaciones');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar una condicion dela cotizacion
    public function destroycondicion(int $condicion_id)
    {
        $condicion = Condicion::find($condicion_id);
        $cotizacion = DB::table('cotizacions as c')
            ->join('condicions as co', 'co.cotizacion_id', '=', 'c.id')
            ->where('co.id', '=', $condicion_id)
            ->select('c.id', 'c.company_id', 'c.cliente_id')->first();
        $company = Company::find($cotizacion->company_id);
        $cliente = Cliente::find($cotizacion->cliente_id);
        if ($condicion) {
            if ($condicion->delete()) {
                $this->crearhistorial('editar', $cotizacion->id, $company->nombre, $cliente->nombre, 'cotizaciones');
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para eliminar un detalle de la cotizacion
    public function destroydetallecotizacion($id)
    {
        $detallecotizacion = Detallecotizacion::find($id);
        $cotizacionh = DB::table('cotizacions as c')
            ->join('detallecotizacions as dc', 'dc.cotizacion_id', '=', 'c.id')
            ->where('dc.id', '=', $id)
            ->select('c.id', 'c.company_id', 'c.cliente_id')->first();
        $companyh = Company::find($cotizacionh->company_id);
        $clienteh = Cliente::find($cotizacionh->cliente_id);
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
                $this->crearhistorial('editar', $cotizacionh->id, $companyh->nombre, $clienteh->nombre, 'cotizaciones');
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para generar el pdf de la cotizacion
    public function generarcotizacionpdf($idcotizacion)
    {
        $coti = Cotizacion::findOrFail($idcotizacion);
        $empresa = Company::findOrFail($coti->company_id);
        $cliente = Cliente::findOrFail($coti->cliente_id);
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
                'c.persona',
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
            ->where('c.id', '=', $idcotizacion)->get();
        $detallekit = DB::table('cotizacions as c')
            ->join('detallecotizacions as dc', 'dc.cotizacion_id', '=', 'c.id')
            ->join('companies as e', 'c.company_id', '=', 'e.id')
            ->join('clientes as cl', 'c.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dc.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select(
                'pk.nombre as producto',
                'k.cantidad',
                'k.product_id as idkit',
            )
            ->where('c.id', '=', $idcotizacion)->get();
        $condiciones = Condicion::all()->where('cotizacion_id', '=', $idcotizacion);
        $fechaletra = $this->obtenerFechaEnLetra($cotizacion[0]->fecha);
        $pdf = PDF::loadView(
            'admin.cotizacion.cotizacionpdf',
            [
                "cotizacion" => $cotizacion, "empresa" => $empresa, "fechaletra" => $fechaletra,
                "cliente" => $cliente, "detallekit" => $detallekit, "condiciones" => $condiciones
            ]
        );
        return $pdf->stream('venta.pdf');
    }
    //funcion para cambiar la fecha de numeros a letras para poner en el pdf
    function obtenerFechaEnLetra($fecha)
    { 
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];
        return $num . ' de ' . $mes . ' del ' . $anno;
    }
    //funcion para saber el dia de la semana
    function conocerDiaSemanaFecha($fecha)
    {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $dia = $dias[date('w', strtotime($fecha))];
        return $dia;
    }
}
