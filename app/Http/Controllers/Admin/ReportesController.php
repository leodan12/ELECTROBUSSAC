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

        return view(
            'admin.reporte.index',
            compact(
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
        } else if ($stock == 'sin'){
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
