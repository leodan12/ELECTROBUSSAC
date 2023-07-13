<?php

namespace App\Http\Controllers\Admin;

use App\Models\Modelocarro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class ModelocarroController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-modelo-carro|editar-modelo-carro|crear-modelo-carro|eliminar-modelo-carro', ['only' => ['index']]);
        $this->middleware('permission:crear-modelo-carro', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-modelo-carro', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-modelo-carro', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-modelo-carro', ['only' => ['showcategoryrestore', 'restaurar']]);
    }

    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('modelocarros as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();

        if ($request->ajax()) {
            $modelocarros = DB::table('modelocarros as mc')
                ->select(
                    'mc.id',
                    'mc.modelo',
                )->where('mc.status', '=', 0);
            return DataTables::of($modelocarros)
                ->addColumn('accion', 'Accion')
                ->editColumn('accion', function ($modelocarros) {
                    return view('admin.modelocarro.botones', compact('modelocarros'));
                })
                ->rawColumns(['accion'])
                ->make(true);
        }
        return view('admin.modelocarro.index', compact('datoseliminados'));
    }
    //vista crear
    public function create()
    {
        return view('admin.modelocarro.create');
    }
    //funcion para guardar un nuevo registro
    public function store(Request $request)
    {
        $this->validate($request, [
            'modelo' => 'required',
        ]);
        $modelocarro = new Modelocarro;
        $modelocarro->modelo = $request->modelo;
        $modelocarro->status = '0';
        $modelocarro->save();
        $this->crearhistorial('crear', $modelocarro->id, $modelocarro->nombre, null, 'modelocarros');
        return redirect('admin/modelocarro')->with('message', 'Modelo de Carro Agregado Satisfactoriamente');
    }
    //vista editar
    public function edit($id)
    {
        $modelo = Modelocarro::find($id);
        return view('admin.modelocarro.edit', compact('modelo'));
    }
    //funcion para actualizar los datos de un registro
    public function update(Request $request,   $id)
    {
        $modelocarro = Modelocarro::find($id);
        $modelocarro->modelo = $request->modelo;
        $modelocarro->update();

        $this->crearhistorial('editar', $modelocarro->id, $modelocarro->nombre, null, 'modelocarros');
        return redirect('admin/modelocarro')->with('message', 'Modelo de Carro Actualizado Satisfactoriamente');
    }
    //funcion para eliminar o solo ocultar un registro
    public function destroy($id)
    {
        $modelo = Modelocarro::find($id);
        if ($modelo) {
            try {
                $modelo->delete();
                $this->crearhistorial('eliminar', $modelo->id, $modelo->nombre, null, 'modelocarros');
                return "1";
            } catch (\Exception $e) {
                $modelo->status = 1;
                if ($modelo->update()) {
                    $this->crearhistorial('eliminar', $modelo->id, $modelo->nombre, null, 'modelocarros');
                    return "1";
                } else {
                    return "0";
                }
            }
        } else return "2";
    }
    //funcion para mostrar los registros que se pueden restaurar
    public function showmodelocarrorestore()
    {
        $modelos =  Modelocarro::all()
            ->where('status', '=', 1);

        return $modelos->values()->all();
    }
    //funcion para restaurar el registro eliminado
    public function restaurar($idregistro)
    {
        $modelo = Modelocarro::find($idregistro);
        if ($modelo) {
            $modelo->status = 0;
            if ($modelo->update()) {
                $this->crearhistorial('restaurar', $modelo->id, $modelo->nombre, null, 'modelocarros');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
