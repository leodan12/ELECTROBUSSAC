<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class ReportesController extends Controller
{
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

    //obtener los datos para el grafico
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
    public function obtenerproductosmasv($empresa)
    {
        $productos = $this->obtenerproductoscantidad($empresa);
        $productosind = $this->productosindividuales($productos);
        $micantidadproductos = $this->sumaproductos($productosind);
        $ordenados = $micantidadproductos->sortByDesc('cantidad');
        $ordenados20 = $ordenados->take(20);
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
            ->select('p.id', 'p.nombre as producto', 'k.cantidad')
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
    public function obtenerclientesmasc($empresa, $tipo)
    {
        $datoscliente = "";
        if ($tipo == "cantidad") {
            $clientescantidad = $this->clientescantidad($empresa);
            $ordenados =   $clientescantidad->sortByDesc('compras');
            $misclientes  = $ordenados->take(20);
            $clientes = $misclientes->values()->all();
            //return $clientes;
            $datoscliente = $this->devolverclientescant($clientes); 

        } else {
            $clientes = $this->clientescosto($empresa);
            $clientesunicos = $this->misclientescosto($clientes);
            $clienteorder =  $clientesunicos->sortByDesc('costo');
            $clientetake = $clienteorder->take(20);
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

    //para los 4 cuadros del index de reportes
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

    //para los cuadros de inicio de sesion

    public function balancemensual()
    {
        $ventames = $this->numeroventas('-1', '');
        $ventacontado = $this->numeroventas('contado', 'SI');
        $ventacredito = $ventames - $ventacontado;
        $ventaxpagar = $this->numeroventas('credito', 'NO');

        $ingresomes = $this->numeroingresos('-1', '');
        $ingresocontado = $this->numeroingresos('contado', 'SI');
        $ingresocredito = $ingresomes - $ingresocontado;
        $ingresoxpagar = $this->numeroingresos('credito', 'NO');

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

    public function numeroventas($formapago, $pagado)
    {
        $fecha = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($fecha . "- $dia days"));
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
    public function numeroingresos($formapago, $pagado)
    {
        $fecha = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($fecha . "- $dia days"));
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
}
