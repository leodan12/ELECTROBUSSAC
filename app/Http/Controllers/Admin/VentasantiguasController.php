<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Ventasantigua;
use Illuminate\Support\Facades\DB;

class VentasantiguasController extends Controller
{ //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy',]]);
    }
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ventas = DB::table('ventasantiguas');
            return DataTables::of($ventas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ventas) {
                    return view('admin.ventaantigua.botones', compact('ventas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.ventaantigua.index');
    }
    //funcion para eliminar un registro de venta
    public function destroy(int $venta_id)
    {
        $venta = Ventasantigua::find($venta_id);
        if ($venta) {
            if ($venta->delete()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar el registro de una venta
    public function show($id)
    {
        $venta =  DB::table('ventasantiguas as va')
            ->where('id', '=', $id)
            ->select(
                'va.id',
                'va.tipo',
                'va.producto',
                'va.preciounitatiosinigv',
                'va.preciototalsinigv',
                'va.moneda',
                'va.cantidad',
                'va.unidad',
                'va.factura',
                'va.fecha',
                'va.detalle',
                'va.cliente',
                'va.empresa',
                'va.devolucion',
                'va.codigo',
                'va.boletafactura'
            )
            ->get();
        return  $venta;
    }
}
