<?php

namespace App\Http\Controllers\Admin;

use App\Models\Carroceria;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Detallecarroceria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class CarroceriaController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-carroceria|editar-carroceria|crear-carroceria|eliminar-carroceria', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-carroceria', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-carroceria', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-carroceria', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-carroceria', ['only' => ['showcarroceriarestore', 'restaurar']]);
    }

    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('carrocerias as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();

        if ($request->ajax()) {
            $carrocerias = DB::table('carrocerias as c')
                ->select(
                    'c.id',
                    'c.tipocarroceria',
                )->where('c.status', '=', 0);
            return DataTables::of($carrocerias)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($carrocerias) {
                    return view('admin.carroceria.botones', compact('carrocerias'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.carroceria.index', compact('datoseliminados'));
    }
    //vista crear 
    public function create()
    {
        $productos = Product::where('status', '=', 0)->get();
        return view('admin.carroceria.create', compact('productos'));
    }
    //funcion para guardar un registro
    public function store(Request $request)
    {
        $this->validate($request, [
            'tipocarroceria' => 'required',
        ]);

        $carroceria = new Carroceria;
        $carroceria->tipocarroceria = $request->tipocarroceria;
        $carroceria->status = '0';
        if ($carroceria->save()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;

            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detalle = new Detallecarroceria;
                    $Detalle->producto_id = $product[$i];
                    $Detalle->cantidad = $cantidad[$i];
                    $Detalle->carroceria_id = $carroceria->id;
                    $Detalle->unidad = $unidad[$i];
                    $Detalle->save();
                }
            }
            $this->crearhistorial('crear', $carroceria->id, $carroceria->tipocarroceria, null, 'carrocerias');
            return redirect('admin/carroceria')->with('message', 'Carroceria Agregada Satisfactoriamente');
        } else {
            return redirect('admin/carroceria')->with('message', 'No se pudo agregar la carroceria');
        }
    }
    //funcion para mostrar los datos de un registro
    public function show($id)
    {
        $carroceria = DB::table('carrocerias as c')
            ->join('detallecarrocerias as dc', 'dc.carroceria_id', '=', 'c.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->select(
                'p.nombre',
                'c.tipocarroceria',
                'dc.cantidad',
                'dc.unidad',
                'c.id'
            )
            ->where('c.id', '=', $id)
            ->get();
        return  $carroceria;
    }
    //vista editar
    public function edit($id)
    {
        $productos = Product::where('status', '=', 0)->get();
        $carroceria = Carroceria::find($id);

        $detalles = DB::table('detallecarrocerias as dc')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->where('carroceria_id', $id)
            ->select('dc.id', 'p.nombre', 'dc.cantidad', 'dc.unidad', 'dc.producto_id')
            ->get();
        return view('admin.carroceria.edit', compact('carroceria', 'detalles', 'productos'));
    }
    //funcion para actualizar los datos de un registro
    public function update(Request $request,  $id)
    {
        $this->validate($request, [
            'tipocarroceria' => 'required',
        ]);

        $carroceria = Carroceria::find($id);
        $carroceria->tipocarroceria = $request->tipocarroceria;
        $carroceria->status = '0';

        if ($carroceria->save()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detalle = new Detallecarroceria;
                    $Detalle->producto_id = $product[$i];
                    $Detalle->cantidad = $cantidad[$i];
                    $Detalle->carroceria_id = $carroceria->id;
                    $Detalle->unidad = $unidad[$i];
                    $Detalle->save();
                }
            }
            $this->crearhistorial('editar', $carroceria->id, $carroceria->tipocarroceria, null, 'carrocerias');
            return redirect('admin/carroceria')->with('message', 'Carroceria Actualizada Satisfactoriamente');
        } else {
            return redirect('admin/carroceria')->with('message', 'No se pudo actualizar la carroceria');
        }
    }
    //funcion para eliminar un registro
    public function destroy($id)
    {
        $carroceria = Carroceria::find($id);
        if ($carroceria) {
            try {
                $carroceria->delete();
                return "1";
            } catch (\Throwable $th) {
                $carroceria->status = 1;
                if ($carroceria->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar un detalle de un rergistro
    public function deletedetalle($id)
    {
        $detalle = Detallecarroceria::find($id);
        if ($detalle) {
            try {
                $detalle->delete();
                return "1";
            } catch (\Throwable $th) {
                $detalle->status = 1;
                if ($detalle->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los registros que se pueden restaurar
    public function showcarroceriarestore()
    {
        $carrocerias =  Carroceria::all()
            ->where('status', '=', 1);

        return $carrocerias->values()->all();
    }
    //funcion para restaurar el registro eliminado
    public function restaurar($idregistro)
    {
        $carroceria = Carroceria::find($idregistro);
        if ($carroceria) {
            $carroceria->status = 0;
            if ($carroceria->update()) {
                $this->crearhistorial('restaurar', $carroceria->id, $carroceria->nombre, null, 'carrocerias');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
    
}
