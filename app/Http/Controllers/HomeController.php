<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    { 
        $usuario = Auth::user()->name;
        return redirect('admin/dashboard'); 
    }
    public function home()
    { 
        $usuario = Auth::user()->name;
        return redirect('admin/dashboard')->with('message','Bienvenido usuario '.$usuario); 
    }
    public function inicio()
    {  
        $sinstock = $this->productossinstock();
        $ventasxcobrar = $this->numeroventas('credito', 'NO', '2010-01-01');
        $ingresosxpagar = $this->numeroingresos('credito', 'NO', '2010-01-01');
        return view('admin.dashboard', compact('sinstock', 'ingresosxpagar', 'ventasxcobrar'));
    }

    public function productossinstock()
    {
        $numerosinstock = 0;
        $prod  = DB::table('inventarios as i')
            ->where('i.status', '=', 0)
            ->select('i.id', 'i.product_id', 'i.stockminimo', 'i.stocktotal')
            ->get();
        for ($i = 0; $i < count($prod); $i++) {
            $p  = DB::table('inventarios as i')
                ->where('i.status', '=', 0)
                ->where('i.product_id', '=', $prod[$i]->product_id)
                ->select('i.id', 'i.product_id', 'i.stockminimo', 'i.stocktotal')
                ->first();
            if ($p->stockminimo >= $prod[$i]->stocktotal) {
                $numerosinstock++;
            }
        }
        return $numerosinstock;
    }

    public function numeroventas($formapago, $pagado, $inicio)
    {
        $ventas = "";

        $ventas = DB::table('ventas as v')
            ->where('v.formapago', '=', $formapago)
            ->where('v.fecha', '>', $inicio)
            ->where('v.pagada', '=', $pagado)
            ->count();

        return   $ventas;
    }
    public function numeroingresos($formapago, $pagado, $inicio)
    {
        $ventas = "";
        $ventas = DB::table('ingresos as i')
            ->where('i.formapago', '=', $formapago)
            ->where('i.fecha', '>', $inicio)
            ->where('i.pagada', '=', $pagado)
            ->count();

        return   $ventas;
    }
}
