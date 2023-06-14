<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Product;

class ReportesController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-reporte',
            ['only' => [
                'index', 'misventas', 'todasfechas', 'ventasdelmes', 'comprasdelmes', 'cotizacionesdelmes', 'obtenerdatosgrafico', 'obtenerproductosmasv', 'obtenerproductoscantidad', 'productosindividuales', 'productosxkit', 'sumaproductos', 'prodseparados', 'obtenerclientesmasc', 'clientescantidad', 'clientescosto', 'misclientescosto', 'obtenerbalance', 'devolverclientes', 'devolverclientescant', 'sumarcostoventa', 'obtenerproductos', 'obtenercotizaciones', 'obtenerventas', 'obteneringresos', 'numeroproductos', 'numerocotizaciones', 'numeroingresos', 'numeroventas', 'balancemensual', 'coninfocompleta', 'obtenerdatosproductosventa', 'obtenerdatosproductoscompra', 'datosproductos', 'infoproductos', 'resultadoventas', 'productosestandar', 'misproductosvendidos', 'datosrotacionstock', 'rotacionstock', 'detallecompras', 'sumarresultado', 'obtenermisventas', 'detalleventas', 'misproductoscomprados', 'obtenermiscompras', 'productosestandar2'
            ]]
        );
    }

    public function index()
    {
        $companies = Company::all();
        $ingresomes = $this->obteneringresos(-1, date('d'));
        $ingresosemana = $this->obteneringresos(-1, date('w'));
        $ingresodia = $this->obteneringresos(-1, 1);

        $ventames = $this->obtenerventas(-1, date('d'));
        $ventasemana = $this->obtenerventas(-1, date('w'));
        $ventadia = $this->obtenerventas(-1, 1);

        $cotizacionmes = $this->obtenercotizaciones(-1, date('d'));
        $cotizacionsemana = $this->obtenercotizaciones(-1, date('w'));
        $cotizaciondia = $this->obtenercotizaciones(-1, 1);

        $productomes = $this->obtenerproductos(-1, "-1");
        $productominimo = $this->obtenerproductos(-1, "minimo");
        $productosinstock = $this->obtenerproductos(-1, "sin");

        $ventas = $this->ventasdelmes('-1');
        $compras = $this->comprasdelmes('-1');
        $cotizacions = $this->cotizacionesdelmes('-1');

        $fechas = $this->todasfechas($ventas, $compras, $cotizacions);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);
        $datoscotizacions = $this->misventas($fechas, $cotizacions);

        //return $datosventas;
        return view(
            'admin.reporte.index',
            compact(
                'fechas',
                'datosventas',
                'datoscompras',
                'datoscotizacions',
                'ingresomes',
                'ingresosemana',
                'ingresodia',
                'ventames',
                'ventasemana',
                'ventadia',
                'cotizacionmes',
                'cotizacionsemana',
                'cotizaciondia',
                'companies',
                'productomes',
                'productominimo',
                'productosinstock',
            )
        );
    }

    //obtener los datos para el reporte grafico
    public function misventas($fechas, $ventas)
    {
        $datosventas = [];
        for ($i = 0; $i < count($fechas); $i++) {
            $sum = 0;
            for ($x = 0; $x < count($ventas); $x++) {
                if ($fechas[$i] == $ventas[$x]->fecha) {
                    if ($ventas[$x]->moneda == 'dolares') {
                        $sum = $sum + (round($ventas[$x]->costoventa * $ventas[$x]->tasacambio, 2));
                    } else {
                        $sum = $sum + $ventas[$x]->costoventa;
                    }
                }
            }
            $datosventas[] = $sum;
        }
        return $datosventas;
    }

    //para compras , ventas y cotizaciones
    public function todasfechas($ventas, $compras, $cotizaciones)
    {
        $fechas = [];
        for ($i = 0; $i < count($ventas); $i++) {
            $fechas[] = $ventas[$i]->fecha;
        }
        for ($i = 0; $i < count($compras); $i++) {
            $fechas[] = $compras[$i]->fecha;
        }
        for ($i = 0; $i < count($cotizaciones); $i++) {
            $fechas[] = $cotizaciones[$i]->fecha;
        }
        $resultado = (array_unique($fechas));
        asort($resultado);
        return array_values($resultado);
    }
    public function ventasdelmes($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('v.fecha', 'v.moneda', 'v.tasacambio')
                ->select('v.fecha', 'v.moneda', 'v.tasacambio', DB::raw('sum(v.costoventa) as costoventa'))
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('v.fecha', 'v.moneda', 'v.tasacambio')
                ->select('v.fecha', 'v.moneda', 'v.tasacambio', DB::raw('sum(v.costoventa) as costoventa'))
                ->get();
        }

        return $ventas;
    }
    public function comprasdelmes($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $compras = "";
        if ($empresa != '-1') {
            $compras = DB::table('ingresos as i')
                ->where('i.company_id', '=', $empresa)
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->groupBy('i.fecha', 'i.moneda', 'i.tasacambio')
                ->select('i.fecha', 'i.moneda', 'i.tasacambio', DB::raw('sum(i.costoventa) as costoventa'))
                ->get();
        } else {
            $compras = DB::table('ingresos as i')
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->groupBy('i.fecha', 'i.moneda', 'i.tasacambio')
                ->select('i.fecha', 'i.moneda', 'i.tasacambio', DB::raw('sum(i.costoventa) as costoventa'))
                ->get();
        }

        return $compras;
    }
    public function cotizacionesdelmes($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $cotizaciones = "";
        if ($empresa != '-1') {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.company_id', '=', $empresa)
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>', $inicio)
                ->groupBy('c.fecha', 'c.moneda', 'c.tasacambio')
                ->select('c.fecha', 'c.moneda', 'c.tasacambio', DB::raw('sum(c.costoventasinigv) as costoventa'))
                ->get();
        } else {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>', $inicio)
                ->groupBy('c.fecha', 'c.moneda', 'c.tasacambio')
                ->select('c.fecha', 'c.moneda', 'c.tasacambio', DB::raw('sum(c.costoventasinigv) as costoventa'))
                ->get();
        }
        return $cotizaciones;
    }
    public function obtenerdatosgrafico($empresa)
    {
        $ventas = $this->ventasdelmes($empresa);
        $compras = $this->comprasdelmes($empresa);
        $cotizacions = $this->cotizacionesdelmes($empresa);

        $fechas = $this->todasfechas($ventas, $compras, $cotizacions);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);
        $datoscotizacions = $this->misventas($fechas, $cotizacions);

        $misdatos = collect();
        $misdatos->put('fechas', $fechas);
        $misdatos->put('datosventas', $datosventas);
        $misdatos->put('datoscompras', $datoscompras);
        $misdatos->put('datoscotizacions', $datoscotizacions);

        return $misdatos;
    }

    //para los productos mas vendidos
    public function obtenerproductosmasv($empresa, $traer)
    {
        $productos = $this->obtenerproductoscantidad($empresa);
        $productosind = $this->productosindividuales($productos);
        $micantidadproductos = $this->sumaproductos($productosind);
        $ordenados = $micantidadproductos->sortByDesc('cantidad');
        $ordenados20 = $ordenados->take($traer);
        $separados = $this->prodseparados($ordenados20->values()->all());
        return $separados;
    }
    public function obtenerproductoscantidad($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('p.tipo', 'p.nombre', 'p.id')
                ->select('p.tipo', 'p.nombre', 'p.id', DB::raw('sum(dv.cantidad) as cantidad'))
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('p.tipo', 'p.nombre', 'p.id')
                ->select('p.tipo', 'p.nombre', 'p.id', DB::raw('sum(dv.cantidad) as cantidad'))
                ->get();
        }

        return $ventas;
    }
    public function productosindividuales($productos)
    {
        $productosL = collect();
        for ($i = 0; $i < count($productos); $i++) {
            if ($productos[$i]->tipo == "estandar") {
                $prod = collect();
                $prod->put('producto', $productos[$i]->nombre);
                $prod->put('cantidad', $productos[$i]->cantidad);
                $productosL->push($prod);
            } else {
                $detalleprod = $this->productosxkit($productos[$i]->id);
                for ($x = 0; $x < count($detalleprod); $x++) {
                    $prod = collect();
                    $prod->put('producto', $detalleprod[$x]->producto);
                    $prod->put('cantidad', ($detalleprod[$x]->cantidad) * $productos[$i]->cantidad);
                    $productosL->push($prod);
                }
            }
        }
        return $productosL;
    }
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad', 'k.preciounitariomo', 'p.tasacambio', 'p.moneda')
            ->get();
        return $productosxkit;
    }
    public function sumaproductos($productos)
    {
        $misproductos = collect();
        $unique = $productos->unique('producto');
        $misproductosunicos = $unique->values()->all();
        for ($i = 0; $i < count($misproductosunicos); $i++) {
            $cont = 0;
            for ($x = 0; $x < count($productos); $x++) {
                if ($misproductosunicos[$i]['producto'] == $productos[$x]['producto']) {
                    $cont = $cont + $productos[$x]['cantidad'];
                }
            }
            $miprod = collect();
            $miprod->put('producto', $misproductosunicos[$i]['producto']);
            $miprod->put('cantidad', $cont);
            $misproductos->push($miprod);
        }

        return $misproductos;
    }
    public function prodseparados($productos)
    {
        $misdatos = collect();
        $prods = [];
        $cant = [];
        for ($i = 0; $i < count($productos); $i++) {
            $prods[] = $productos[$i]['producto'];
            $cant[] = $productos[$i]['cantidad'];
        }
        $misdatos->put('productos', $prods);
        $misdatos->put('cantidades', $cant);

        return $misdatos;
    }
    //para los clientes con mas compras
    public function obtenerclientesmasc($empresa, $tipo, $traer)
    {
        $datoscliente = "";
        if ($tipo == "cantidad") {
            $clientescantidad = $this->clientescantidad($empresa);
            $ordenados =   $clientescantidad->sortByDesc('compras');
            $misclientes  = $ordenados->take($traer);
            $clientes = $misclientes->values()->all();
            //return $clientes;
            $datoscliente = $this->devolverclientescant($clientes);
        } else {
            $clientes = $this->clientescosto($empresa);
            $clientesunicos = $this->misclientescosto($clientes);
            $clienteorder =  $clientesunicos->sortByDesc('costo');
            $clientetake = $clienteorder->take($traer);
            $client = $clientetake->values()->all();
            $datoscliente = $this->devolverclientes($client);
        }
        return  $datoscliente;
    }
    public function clientescantidad($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('c.nombre')
                ->select('c.nombre', DB::raw('count(v.id) as compras'))
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->groupBy('c.nombre')
                ->select('c.nombre', DB::raw('count(v.id) as compras'))
                ->get();
        }

        return $ventas;
    }
    public function clientescosto($empresa)
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->select('c.nombre', 'v.moneda', 'tasacambio', 'v.costoventa')
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->select('c.nombre', 'v.moneda', 'tasacambio', 'v.costoventa')
                ->get();
        }
        return $ventas;
    }
    public function misclientescosto($clientes)
    {
        $unicos = $clientes->unique('nombre');
        $clientesunicos = $unicos->values()->all();
        $misclientes = collect();

        for ($i = 0; $i < count($clientesunicos); $i++) {
            $sum = 0;
            for ($x = 0; $x < count($clientes); $x++) {
                if ($clientesunicos[$i]->nombre == $clientes[$x]->nombre) {
                    if ($clientes[$x]->moneda == "dolares") {
                        $sum = $sum + round(($clientes[$x]->costoventa) * $clientes[$x]->tasacambio, 2);
                    } else {
                        $sum = $sum + ($clientes[$x]->costoventa);
                    }
                }
            }
            $miclient = collect();
            $miclient->put('cliente', $clientesunicos[$i]->nombre);
            $miclient->put('costo', $sum);
            $misclientes->push($miclient);
        }

        return $misclientes;
    }
    public function devolverclientescant($clientes)
    {
        $misdatos = collect();
        $cliente = [];
        $cant = [];
        for ($i = 0; $i < count($clientes); $i++) {
            $cliente[] = $clientes[$i]->nombre;
            $cant[] = $clientes[$i]->compras;
        }
        $misdatos->put('clientes', $cliente);
        $misdatos->put('costos', $cant);

        return $misdatos;
    }
    public function devolverclientes($clientes)
    {
        $misdatos = collect();
        $cliente = [];
        $costo = [];
        for ($i = 0; $i < count($clientes); $i++) {
            $cliente[] = $clientes[$i]['cliente'];
            $costo[] = $clientes[$i]['costo'];
        }
        $misdatos->put('clientes', $cliente);
        $misdatos->put('costos', $costo);

        return $misdatos;
    }

    //-----------------------para los 4 cuadros del index de reportes
    public function obtenerbalance($idempresa)
    {
        $ingresomes = $this->obteneringresos($idempresa, date('d'));
        $ingresosemana = $this->obteneringresos($idempresa, date('w'));
        $ingresodia = $this->obteneringresos($idempresa, 1);

        $ventames = $this->obtenerventas($idempresa, date('d'));
        $ventasemana = $this->obtenerventas($idempresa, date('w'));
        $ventadia = $this->obtenerventas($idempresa, 1);

        $cotizacionmes = $this->obtenercotizaciones($idempresa, date('d'));
        $cotizacionsemana = $this->obtenercotizaciones($idempresa, date('w'));
        $cotizaciondia = $this->obtenercotizaciones($idempresa, 1);

        $productomes = $this->obtenerproductos($idempresa, "-1");
        $productominimo = $this->obtenerproductos($idempresa, "minimo");
        $productosinstock = $this->obtenerproductos($idempresa, "sin");


        $resultados = collect();
        $resultados->put('ingresomes', $ingresomes);
        $resultados->put('ingresosemana', $ingresosemana);
        $resultados->put('ingresodia', $ingresodia);
        $resultados->put('ventames', $ventames);
        $resultados->put('ventasemana', $ventasemana);
        $resultados->put('ventadia', $ventadia);
        $resultados->put('cotizacionmes', $cotizacionmes);
        $resultados->put('cotizacionsemana', $cotizacionsemana);
        $resultados->put('cotizaciondia', $cotizaciondia);
        $resultados->put('productomes', $productomes);
        $resultados->put('productominimo', $productominimo);
        $resultados->put('productosinstock', $productosinstock);

        return $resultados;
    }
    public function obteneringresos($idempresa, $dia)
    {
        $hoy = date('Y-m-d');
        //$dia = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));
        $ingresosmes = 0;
        if ($idempresa != -1) {
            $ingresos = DB::table('ingresos as i')
                ->where('i.company_id', '=', $idempresa)
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->get();
            $ingresosmes = $this->sumarcostoventa($ingresos, 0);
        } else {
            $ingresos = DB::table('ingresos as i')
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->get();
            $ingresosmes = $this->sumarcostoventa($ingresos, 0);
        }
        return   $ingresosmes;
    }
    public function obtenerventas($idempresa, $dia)
    {
        $hoy = date('Y-m-d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));
        $ventasmes = 0;
        if ($idempresa != -1) {
            $ventas = DB::table('ventas as v')
                ->where('v.company_id', '=', $idempresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->get();
            $ventasmes = $this->sumarcostoventa($ventas, 0);
        } else {
            $ventas = DB::table('ventas as v')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>', $inicio)
                ->get();
            $ventasmes = $this->sumarcostoventa($ventas, 0);
        }
        return   $ventasmes;
    }
    public function obtenercotizaciones($idempresa, $dia)
    {
        $hoy = date('Y-m-d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));
        $cotizacionmes = 0;
        if ($idempresa != -1) {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.company_id', '=', $idempresa)
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>', $inicio)
                ->get();
            $cotizacionmes = $this->sumarcostoventa($cotizaciones, 1);
        } else {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>', $inicio)
                ->get();
            $cotizacionmes = $this->sumarcostoventa($cotizaciones, 1);
        }
        return   $cotizacionmes;
    }
    public function obtenerproductos($idempresa, $stock)
    {
        $productos = 0;
        if ($stock == '-1') {
            if ($idempresa == '-1') {
                $productos = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->count();
            } else {
                $productos = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->count();
            }
        } else if ($stock == 'minimo') {
            $cont = 0;
            if ($idempresa == '-1') {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stocktotal <= $prod[$i]->stockminimo) {
                        $cont++;
                    }
                }
            } else {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stockempresa <= $prod[$i]->stockminimo) {
                        $cont++;
                    }
                }
            }
            $productos = $cont;
        } else if ($stock == 'sin') {
            $cont = 0;
            if ($idempresa == '-1') {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stocktotal == 0) {
                        $cont++;
                    }
                }
            } else {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stockempresa == 0) {
                        $cont++;
                    }
                }
            }
            $productos = $cont;
        }
        return $productos;
    }
    public function sumarcostoventa($ingresos, $c)
    {
        $costoventa = 0;
        for ($i = 0; $i < count($ingresos); $i++) {
            if ($ingresos[$i]->moneda == 'dolares') {
                if ($c == 1) {
                    $costoventa =  $costoventa + ($ingresos[$i]->costoventasinigv * $ingresos[$i]->tasacambio);
                } else {
                    $costoventa =  $costoventa + ($ingresos[$i]->costoventa * $ingresos[$i]->tasacambio);
                }
            } else {
                if ($c == 1) {
                    $costoventa =  $costoventa + $ingresos[$i]->costoventasinigv;
                } else {
                    $costoventa =  $costoventa + $ingresos[$i]->costoventa;
                }
            }
        }
        return  round($costoventa, 2);
    }

    //------------------------para los cuadros de inicio de sesion
    public function balancemensual()
    {
        $fecha = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($fecha . "- $dia days"));

        $ventames = $this->numeroventas('-1', '', $inicio);
        $ventacontado = $this->numeroventas('contado', 'SI', $inicio);
        $ventacredito = $ventames - $ventacontado;
        $ventaxpagar = $this->numeroventas('credito', 'NO', '2010-01-01');

        $ingresomes = $this->numeroingresos('-1', '', $inicio);
        $ingresocontado = $this->numeroingresos('contado', 'SI', $inicio);
        $ingresocredito = $ingresomes - $ingresocontado;
        $ingresoxpagar = $this->numeroingresos('credito', 'NO', '2010-01-01');

        $cotizacionmes = $this->numerocotizaciones('-1', '');
        $cotizacioncontado = $this->numerocotizaciones('contado', '');
        $cotizacioncredito = $cotizacionmes - $cotizacioncontado;
        $cotizacionvendida = $this->numerocotizaciones('-1', 'SI');

        $producto = $this->numeroproductos('');
        $productostock = $this->numeroproductos('stock');
        $productominimo = $this->numeroproductos('minimo');
        $productosin = $this->numeroproductos('sin');

        //return $productosin;

        $datos = collect();
        $datos->put('ventames', $ventames);
        $datos->put('ventacontado', $ventacontado);
        $datos->put('ventacredito', $ventacredito);
        $datos->put('ventaxpagar', $ventaxpagar);
        $datos->put('ingresomes', $ingresomes);
        $datos->put('ingresocontado', $ingresocontado);
        $datos->put('ingresocredito', $ingresocredito);
        $datos->put('ingresoxpagar', $ingresoxpagar);
        $datos->put('cotizacionmes', $cotizacionmes);
        $datos->put('cotizacioncontado', $cotizacioncontado);
        $datos->put('cotizacioncredito', $cotizacioncredito);
        $datos->put('cotizacionvendida', $cotizacionvendida);
        $datos->put('producto', $producto);
        $datos->put('productostock', $productostock);
        $datos->put('productominimo', $productominimo);
        $datos->put('productosin', $productosin);

        return $datos;
    }
    public function numeroventas($formapago, $pagado, $inicio)
    {
        $fecha = date('Y-m-d');

        $ventas = "";
        if ($formapago != '-1') {
            $ventas = DB::table('ventas as v')
                ->where('v.formapago', '=', $formapago)
                ->where('v.fecha', '>', $inicio)
                ->where('v.pagada', '=', $pagado)
                ->count();
        } else {
            $ventas = DB::table('ventas as v')
                ->where('v.fecha', '>', $inicio)
                ->count();
        }
        return   $ventas;
    }
    public function numeroingresos($formapago, $pagado, $inicio)
    {
        $fecha = date('Y-m-d');

        $ventas = "";
        if ($formapago != '-1') {
            $ventas = DB::table('ingresos as i')
                ->where('i.formapago', '=', $formapago)
                ->where('i.fecha', '>', $inicio)
                ->where('i.pagada', '=', $pagado)
                ->count();
        } else {
            $ventas = DB::table('ingresos as i')
                ->where('i.fecha', '>', $inicio)
                ->count();
        }
        return   $ventas;
    }
    public function numerocotizaciones($formapago, $vendida)
    {
        $fecha = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($fecha . "- $dia days"));
        $ventas = "";
        if ($formapago != '-1') {
            $ventas = DB::table('cotizacions as c')
                ->where('c.formapago', '=', $formapago)
                ->where('c.fecha', '>', $inicio)
                ->count();
        } else {
            if ($vendida == "SI") {
                $ventas = DB::table('cotizacions as c')
                    ->where('c.fecha', '>', $inicio)
                    ->where('c.vendida', '=', $vendida)
                    ->count();
            } else {
                $ventas = DB::table('cotizacions as c')
                    ->where('c.fecha', '>', $inicio)
                    ->count();
            }
        }
        return   $ventas;
    }
    public function numeroproductos($stock)
    {
        $productos = "";
        if ($stock == 'stock') {
            $cont = 0;
            $prod  = DB::table('products as p')
                ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                ->get();
            for ($i = 0; $i < count($prod); $i++) {
                if ($prod[$i]->stockminimo < $prod[$i]->stocktotal) {
                    $cont++;
                }
            }
            $productos = $cont;
        } else if ($stock == 'minimo') {
            $cont = 0;
            $prod  = DB::table('products as p')
                ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                ->get();
            for ($i = 0; $i < count($prod); $i++) {
                if ($prod[$i]->stockminimo >= $prod[$i]->stocktotal) {
                    $cont++;
                }
            }
            $productos = $cont;
        } else if ($stock == 'sin') {
            $productos = DB::table('products as p')
                ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                ->where('i.stocktotal', '=', 0)
                ->count();
        } else {
            $productos = DB::table('products as p')
                ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                ->count();
        }
        return   $productos;
    }

    //----------para traer datos de las ventas y compras de los productos por empresa o producto y fechas-------------------------

    public function infoproductos()
    {
        $companies = Company::all();
        $productos = Product::all();
        return view('admin.reporte.infoproductos', compact('companies', 'productos'));
    }
    public function datosproductos($fechainicio, $fechafin, $empresa, $producto)
    {
        $miscompras = $this->obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto);
        $misventas = $this->obtenerdatosproductosventa($fechainicio, $fechafin, $empresa, $producto);

        $kitsventas = $this->todoskits($fechainicio, $fechafin, $empresa, "venta");
        $kitscompras = $this->todoskits($fechainicio, $fechafin, $empresa, "compra");

        $productokits = $this->todosestandarkit($kitscompras, $kitsventas, $producto);

        $datos = $this->coninfocompleta($miscompras, $misventas);
         
        $unidos = $datos->concat($productokits);
         
        $unidosensoles = $this->ventasycomprasensoles($unidos);

        return $unidosensoles;
    }
    public function ventasycomprasensoles($datos)
    {
        $todoslosdatos = collect();
        for ($i = 0; $i < count($datos); $i++) {
            $preciof = 0;
            $preciou = 0;
            if ($datos[$i]['moneda'] == 'dolares') {
                $preciof = round($preciof + round($datos[$i]['preciofinal'] * $datos[$i]['tasacambio'], 2), 2);
                $preciou = round($preciou + round($datos[$i]['preciounitariomo'] * $datos[$i]['tasacambio'], 2), 2);
            } else {
                $preciof = round($preciof + $datos[$i]['preciofinal'], 2);
                $preciou = round($preciou + $datos[$i]['preciounitariomo'], 2);
            }
            $micompra = collect();
            $micompra->put('compraventa', $datos[$i]['compraventa']);
            $micompra->put('empresa', $datos[$i]['empresa']);
            $micompra->put('factura', $datos[$i]['factura']);
            $micompra->put('cliente', $datos[$i]['cliente']);
            $micompra->put('producto', $datos[$i]['producto']);
            $micompra->put('cantidad', $datos[$i]['cantidad']);
            $micompra->put('preciounitariomo', $preciou);
            $micompra->put('preciofinal', $preciof);
            $micompra->put('moneda', "soles");
            $micompra->put('fecha', $datos[$i]['fecha']);
            $micompra->put('tipo', $datos[$i]['tipo']);

            $todoslosdatos->push($micompra);
        }
        return  $todoslosdatos;
    }
    public function obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto)
    {
        $compras = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio'
                    )
                    ->get();
            } else {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio'
                    )
                    ->get();
            } else {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio'
                    )
                    ->get();
            }
        }
        return $compras;
    }
    public function obtenerdatosproductosventa($fechainicio, $fechafin, $empresa, $producto)
    {
        $ventas = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio'
                    )
                    ->get();
            }
        }
        return $ventas;
    }
    public function coninfocompleta($miscompras, $misventas)
    {
        $todoslosdatos = collect();
        for ($i = 0; $i < count($miscompras); $i++) {
            $micompra = collect();
            $micompra->put('compraventa', 'INGRESO');
            $micompra->put('empresa', $miscompras[$i]->empresa);
            $micompra->put('factura', $miscompras[$i]->factura);
            $micompra->put('cliente', $miscompras[$i]->cliente);
            $micompra->put('producto', $miscompras[$i]->producto);
            $micompra->put('cantidad', $miscompras[$i]->cantidad);
            $micompra->put('preciounitariomo', $miscompras[$i]->preciounitariomo);
            $micompra->put('preciofinal', $miscompras[$i]->preciofinal);
            $micompra->put('moneda', $miscompras[$i]->moneda);
            $micompra->put('fecha', $miscompras[$i]->fecha);
            $micompra->put('tipo', $miscompras[$i]->tipo);
            $micompra->put('tasacambio', $miscompras[$i]->tasacambio);

            $todoslosdatos->push($micompra);
        }
        for ($x = 0; $x < count($misventas); $x++) {
            $miventa = collect();
            $miventa->put('compraventa', 'VENTA');
            $miventa->put('empresa', $misventas[$x]->empresa);
            $miventa->put('factura', $misventas[$x]->factura);
            $miventa->put('cliente', $misventas[$x]->cliente);
            $miventa->put('producto', $misventas[$x]->producto);
            $miventa->put('cantidad', $misventas[$x]->cantidad);
            $miventa->put('preciounitariomo', $misventas[$x]->preciounitariomo);
            $miventa->put('preciofinal', $misventas[$x]->preciofinal);
            $miventa->put('moneda', $misventas[$x]->moneda);
            $miventa->put('fecha', $misventas[$x]->fecha);
            $miventa->put('tipo', $misventas[$x]->tipo);
            $miventa->put('tasacambio', $misventas[$x]->tasacambio);
            $todoslosdatos->push($miventa);
        }
        return $todoslosdatos;
    }
    public function todoskits($fechainicio, $fechafin, $empresa, $compraventa)
    {
        $registros = "";
        if ($compraventa == "compra") {
            if ($empresa != "-1") {
                $registros = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.id as idproducto',
                        'i.tasacambio'
                    )
                    ->get();
            } else {
                $registros = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.id as idproducto',
                        'i.tasacambio'
                    )
                    ->get();
            }
        } else if ($compraventa == "venta") {
            if ($empresa != "-1") {
                $registros = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.id as idproducto',
                        'v.tasacambio'
                    )
                    ->get();
            } else {
                $registros = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.id as idproducto',
                        'v.tasacambio'
                    )
                    ->get();
            }
        }
        return $registros;
    }
    public function todosestandarkit($compras, $ventas, $idproducto)
    {
        $resultados = collect();

        for ($i = 0; $i < count($compras); $i++) {
            $prodkit = $this->productosxkit($compras[$i]->idproducto);

            $mikit = DB::table('products as p')
                ->where('p.id', '=', $compras[$i]->idproducto)
                ->select(
                    'p.nombre as producto',
                    'p.id',
                    'p.moneda',
                    'p.tasacambio',
                    'p.NoIGV',
                )
                ->first();

            for ($k = 0; $k < count($prodkit); $k++) {
                if ($prodkit[$k]->id == $idproducto) {
                    $preciounit = 0;
                    $preciofinal = 0;
                    if ($compras[$i]->moneda == $prodkit[$k]->moneda) {
                        $preciounit = $prodkit[$k]->preciounitariomo;
                        $preciofinal = round($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $compras[$i]->cantidad, 2);
                    } else
                    if ($compras[$i]->moneda == "soles" && $prodkit[$k]->moneda == "dolares") {
                        $preciounit = round($prodkit[$k]->preciounitariomo * $mikit->tasacambio, 2);
                        $preciofinal = round($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $compras[$i]->cantidad * $mikit->tasacambio, 2);
                    } else if ($compras[$i]->moneda == "dolares" && $prodkit[$k]->moneda == "soles") {
                        $preciounit = round($prodkit[$k]->preciounitariomo / $mikit->tasacambio, 2);
                        $preciofinal = round(($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $compras[$i]->cantidad) / $mikit->tasacambio, 2);
                    }
                    $prod = collect();
                    $prod->put('compraventa', 'INGRESO');
                    $prod->put('empresa', $compras[$i]->empresa);
                    $prod->put('factura', $compras[$i]->factura);
                    $prod->put('cliente', $compras[$i]->cliente);
                    $prod->put('producto', $prodkit[$k]->producto);
                    $prod->put('cantidad', $prodkit[$k]->cantidad * $compras[$i]->cantidad);
                    $prod->put('preciounitariomo', $preciounit);
                    $prod->put('preciofinal', $preciofinal);
                    $prod->put('moneda', $compras[$i]->moneda);
                    $prod->put('fecha', $compras[$i]->fecha);
                    $prod->put('tasacambio', $compras[$i]->tasacambio);
                    $prod->put('tipo', "kit");
                    $resultados->push($prod);
                }
            }
        }

        for ($i = 0; $i < count($ventas); $i++) {
            $prodkit = $this->productosxkit($ventas[$i]->idproducto);

            $mikit = DB::table('products as p')
                ->where('p.id', '=', $ventas[$i]->idproducto)
                ->select(
                    'p.nombre as producto',
                    'p.id',
                    'p.moneda',
                    'p.tasacambio',
                    'p.NoIGV',
                )
                ->first();

            for ($k = 0; $k < count($prodkit); $k++) {
                if ($prodkit[$k]->id == $idproducto) {
                    $preciounit = 0;
                    $preciofinal = 0;
                    if ($ventas[$i]->moneda == $prodkit[$k]->moneda) {
                        $preciounit = $prodkit[$k]->preciounitariomo;
                        $preciofinal = round($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $ventas[$i]->cantidad, 2);
                    } else
                    if ($ventas[$i]->moneda == "soles" && $prodkit[$k]->moneda == "dolares") {
                        $preciounit = round($prodkit[$k]->preciounitariomo * $mikit->tasacambio, 2);
                        $preciofinal = round($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $ventas[$i]->cantidad * $mikit->tasacambio, 2);
                    } else if ($ventas[$i]->moneda == "dolares" && $prodkit[$k]->moneda == "soles") {
                        $preciounit = round($prodkit[$k]->preciounitariomo / $mikit->tasacambio, 2);
                        $preciofinal = round(($prodkit[$k]->preciounitariomo * $prodkit[$k]->cantidad * $ventas[$i]->cantidad) / $mikit->tasacambio, 2);
                    }
                    $prod = collect();
                    $prod->put('compraventa', 'VENTA');
                    $prod->put('empresa', $ventas[$i]->empresa);
                    $prod->put('factura', $ventas[$i]->factura);
                    $prod->put('cliente', $ventas[$i]->cliente);
                    $prod->put('producto', $prodkit[$k]->producto);
                    $prod->put('cantidad', $prodkit[$k]->cantidad * $ventas[$i]->cantidad);
                    $prod->put('preciounitariomo', $preciounit);
                    $prod->put('preciofinal', $preciofinal);
                    $prod->put('moneda', $ventas[$i]->moneda);
                    $prod->put('fecha', $ventas[$i]->fecha);
                    $prod->put('tasacambio', $ventas[$i]->tasacambio);
                    $prod->put('tipo', "kit");
                    $resultados->push($prod);
                }
            }
        }

        return $resultados;
    }
    //------------------------------para obtener la rotacion del inventario--------------------------------------------
    public function rotacionstock()
    {
        $companies = Company::all();
        $productos = Product::all()->where('tipo', '=', 'estandar');
        return view('admin.reporte.rotacionstock', compact('companies', 'productos'));
    }
    public function datosrotacionstock($fechainicio, $fechafin, $empresa, $producto)
    {
        $misventas = $this->misproductosvendidos($fechainicio, $fechafin, $empresa, $producto);
        $misventasestandar = $this->productosestandar($misventas, $producto);
        $miresultadoventas = $this->resultadoventas($misventasestandar, 'venta');
        $miscompras = $this->misproductoscomprados($fechainicio, $fechafin, $empresa, $producto);
        $miscomprasestandar = $this->productosestandar($miscompras, $producto);
        $miresultadocompras = $this->resultadoventas($miscomprasestandar, 'compra');

        //return $misventas;
        $resultadostotales = $miresultadoventas->concat($miresultadocompras);

        return $resultadostotales;
    }
    public function misproductosvendidos($fechainicio, $fechafin, $empresa, $producto)
    {
        $misproductos = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $productos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                    ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->where('kp.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $productos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                    ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->where('kp.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
            }
        }
        return $misproductos;
    }
    public function productosestandar($misventas, $producto)
    {
        $resultado = collect();
        for ($i = 0; $i < count($misventas); $i++) {
            if ($misventas[$i]->tipo == 'kit') {
                $misprod = $this->productosxkit($misventas[$i]->idproducto);
                for ($x = 0; $x < count($misprod); $x++) {
                    $costoprod = 0;
                    $costoprod =  $misprod[$x]->preciounitariomo;
                    if ($misventas[$i]->moneda == $misprod[$x]->moneda) {
                        $costoprod = $costoprod;
                    } else if ($misventas[$i]->moneda == 'dolares' && $misprod[$x]->moneda = 'soles') {
                        $costoprod = round($costoprod / $misventas[$i]->tasacambio, 2);
                    } else if ($misventas[$i]->moneda == 'soles' && $misprod[$x]->moneda = 'dolares') {
                        $costoprod = round($costoprod * $misventas[$i]->tasacambio, 2);
                    }
                    if ($misprod[$x]->id == $producto) {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoprod);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $resultado->push($venta);
                    }
                    if ($producto == "-1") {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoprod);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $resultado->push($venta);
                    }
                }
            } else {
                $venta = collect();
                $venta->put('empresa', $misventas[$i]->empresa);
                $venta->put('producto', $misventas[$i]->producto);
                $venta->put('cantidad', $misventas[$i]->cantidad);
                $venta->put('moneda', $misventas[$i]->moneda);
                $venta->put('tasacambio', $misventas[$i]->tasacambio);
                $venta->put('preciofinal', $misventas[$i]->preciounitariomo);
                $venta->put('fecha', $misventas[$i]->fecha);
                $resultado->push($venta);
            }
        }
        return $resultado;
    }
    public function resultadoventas($misventas, $compraventa)
    {
        $resultado = collect();

        $unicas = $misventas->unique(function ($item) {
            return $item['empresa'] . $item['producto'];
        });

        $misventasunicas = $unicas->values()->all();

        for ($x = 0; $x < count($misventasunicas); $x++) {
            $sumcant = 0;
            $sumcosto = 0;
            $minimo = 100000;
            $maximo = 0;
            //el $misventas[$i]['preciofinal'] es el precio unirario
            for ($i = 0; $i < count($misventas); $i++) {
                if (
                    $misventas[$i]['producto'] == $misventasunicas[$x]['producto'] &&
                    $misventas[$i]['empresa'] == $misventasunicas[$x]['empresa']
                ) {
                    if ($misventas[$i]['moneda'] == "soles") {
                        $sumcosto = round($sumcosto + round(($misventas[$i]['cantidad'] * $misventas[$i]['preciofinal']) / $misventas[$i]['tasacambio'], 2), 2);
                        if ($maximo < round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2)) {
                            $maximo = round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2);
                        }
                        if ($minimo > round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2)) {
                            $minimo = round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2);
                        }
                    } else {
                        $sumcosto = round($sumcosto + round($misventas[$i]['cantidad'] * $misventas[$i]['preciofinal'], 2), 2);
                        if ($maximo < $misventas[$i]['preciofinal']) {
                            $maximo = $misventas[$i]['preciofinal'];
                        }
                        if ($minimo > $misventas[$i]['preciofinal']) {
                            $minimo = $misventas[$i]['preciofinal'];
                        }
                    }
                    $sumcant = $sumcant + $misventas[$i]['cantidad'];
                }
            }
            $producto = collect();
            $producto->put('empresa', $misventasunicas[$x]['empresa']);
            $producto->put('compraventa', $compraventa);
            $producto->put('producto', $misventasunicas[$x]['producto']);
            $producto->put('cantidad', $sumcant);
            $producto->put('maximo', $maximo);
            $producto->put('minimo', $minimo);
            $producto->put('preciofinal', $sumcosto);
            $producto->put('moneda', "dolares");
            $producto->put('fecha', $misventasunicas[$x]['fecha']);
            $resultado->push($producto);
        }
        return $resultado;
    }
    public function misproductoscomprados($fechainicio, $fechafin, $empresa, $producto)
    {
        $misproductos = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $productos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();

                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                    ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->where('kp.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $productos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                    ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->where('kp.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto'
                    )
                    ->get();
            }
        }
        return $misproductos;
    }

    //otros
    public function detalleventas($fechainicio, $fechafin, $empresa, $producto)
    {
        $misventas = $this->obtenermisventas($fechainicio, $fechafin, $empresa, $producto);
        $misventasestandar = $this->productosestandar2($misventas, $producto);
        $resultado  = $this->sumarresultado($misventasestandar, 'venta');

        return $resultado;
    }
    public function obtenermisventas($fechainicio, $fechafin, $empresa, $producto)
    {
        $productos = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->where('v.fecha', '<=', $fechafin)
            ->where('v.fecha', '>=', $fechainicio)
            ->where('e.nombre', '=', $empresa)
            ->where('p.nombre', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'dv.cantidad',
                'v.moneda',
                'v.tasacambio',
                'dv.preciofinal',
                'v.fecha',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();
        $miskits = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
            ->where('v.fecha', '<=', $fechafin)
            ->where('v.fecha', '>=', $fechainicio)
            ->where('e.nombre', '=', $empresa)
            ->where('p.tipo', '=', "kit")
            ->where('kp.nombre', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'dv.cantidad',
                'v.moneda',
                'v.tasacambio',
                'dv.preciofinal',
                'v.fecha',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();

        $misproductos = $productos->concat($miskits);
        return $misproductos;
    }
    public function sumarresultado($misventas, $compraventa)
    {
        $resultado = collect();
        $unica = $misventas->unique('cliente');
        $unicaempresa = $unica->values()->all();
        for ($x = 0; $x < count($unicaempresa); $x++) {
            $sumcant = 0;
            $sumcosto = 0;
            for ($i = 0; $i < count($misventas); $i++) {
                if ($misventas[$i]['cliente'] == $unicaempresa[$x]['cliente']) {
                    if ($misventas[$i]['moneda'] == "dolares") {
                        $sumcosto = round($sumcosto + $misventas[$i]['preciofinal'], 2);  //+ round($misventas[$i]['tasacambio'] * $misventas[$i]['preciofinal'], 2);
                    } else {
                        $sumcosto = round($sumcosto + round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2), 2);
                    }
                    $sumcant = $sumcant + $misventas[$i]['cantidad'];
                }
            }
            $producto = collect();
            $producto->put('empresa', $unicaempresa[$x]['empresa']);
            $producto->put('cliente', $unicaempresa[$x]['cliente']);
            $producto->put('compraventa', $compraventa);
            $producto->put('producto', $unicaempresa[$x]['producto']);
            $producto->put('cantidad', $sumcant);
            $producto->put('preciofinal', $sumcosto);
            $producto->put('moneda', "dolares");
            $producto->put('fecha', $unicaempresa[$x]['fecha']);
            $resultado->push($producto);
        }
        return $resultado;
    }
    public function detallecompras($fechainicio, $fechafin, $empresa, $producto)
    {
        $miscompras = $this->obtenermiscompras($fechainicio, $fechafin, $empresa, $producto);
        $miscomprasestandar = $this->productosestandar2($miscompras, $producto);
        $resultado  = $this->sumarresultado($miscomprasestandar, 'compra');

        return $resultado;
    }
    public function obtenermiscompras($fechainicio, $fechafin, $empresa, $producto)
    {
        $productos = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->where('i.fecha', '<=', $fechafin)
            ->where('i.fecha', '>=', $fechainicio)
            ->where('e.nombre', '=', $empresa)
            ->where('p.nombre', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'di.cantidad',
                'i.moneda',
                'i.tasacambio',
                'di.preciofinal',
                'i.fecha',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();
        $miskits = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as kp', 'k.kitproduct_id', '=', 'kp.id')
            ->where('i.fecha', '<=', $fechafin)
            ->where('i.fecha', '>=', $fechainicio)
            ->where('e.nombre', '=', $empresa)
            ->where('p.tipo', '=', "kit")
            ->where('kp.nombre', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'di.cantidad',
                'i.moneda',
                'i.tasacambio',
                'di.preciofinal',
                'i.fecha',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();
        $misproductos = $productos->concat($miskits);
        return $misproductos;
    }
    public function productosestandar2($misventas, $producto)
    {
        $resultado = collect();
        for ($i = 0; $i < count($misventas); $i++) {
            if ($misventas[$i]->tipo == 'kit') {
                $misprod = $this->productosxkit($misventas[$i]->idproducto);
                for ($x = 0; $x < count($misprod); $x++) {
                    $costoventa = 0;
                    $costoventa = round(($misventas[$i]->cantidad * $misprod[$x]->cantidad) * $misprod[$x]->preciounitariomo, 2);
                    if ($misventas[$i]->moneda == $misprod[$x]->moneda) {
                        $costoventa = $costoventa;
                    } else if ($misventas[$i]->moneda == 'dolares' && $misprod[$x]->moneda = 'soles') {
                        $costoventa = round($costoventa / $misventas[$i]->tasacambio, 2);
                    } else if ($misventas[$i]->moneda == 'soles' && $misprod[$x]->moneda = 'dolares') {
                        $costoventa = round($costoventa * $misventas[$i]->tasacambio, 2);
                    }
                    if ($misprod[$x]->producto == $producto) {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cliente', $misventas[$i]->cliente);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoventa);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $resultado->push($venta);
                    }
                    if ($producto == "-1") {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cliente', $misventas[$i]->cliente);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoventa);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $resultado->push($venta);
                    }
                }
            } else {
                $venta = collect();
                $venta->put('empresa', $misventas[$i]->empresa);
                $venta->put('producto', $misventas[$i]->producto);
                $venta->put('cliente', $misventas[$i]->cliente);
                $venta->put('cantidad', $misventas[$i]->cantidad);
                $venta->put('moneda', $misventas[$i]->moneda);
                $venta->put('tasacambio', $misventas[$i]->tasacambio);
                $venta->put('preciofinal', $misventas[$i]->preciofinal);
                $venta->put('fecha', $misventas[$i]->fecha);
                $resultado->push($venta);
            }
        }
        return $resultado;
    }
}
