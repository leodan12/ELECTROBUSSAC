<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Historial;

class HistorialController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-historial|eliminar-historial', ['only' => ['index']]);
        $this->middleware('permission:eliminar-historial', ['only' => ['destroy']]);
    }
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $historiales = DB::table('historials as h')
                ->join('users as u', 'h.usuario_id', '=', 'u.id')
                ->select(
                    'h.id',
                    'u.name as usuario',
                    'h.fecha',
                    'h.accion',
                    'h.tabla',
                    'h.registro_id',
                    'h.dato1',
                    'h.dato2',
                );
            return DataTables::of($historiales)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($historiales) {
                    return view('admin.historial.botones', compact('historiales'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.historial.index');
    }
    //funcion para eliminar un registro
    public function destroy($id)
    {
        $historial = Historial::find($id);
        if ($historial) {
            if ($historial->delete()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar todos los registros de la tabla
    public function limpiartabla()
    {
        $historiales = DB::table('historials');
        if ($historiales) {
            if ($historiales->delete()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
