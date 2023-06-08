<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteFormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Ingreso;
use App\Models\Inventario;
use App\Models\Cotizacion;
use App\Models\Venta;
use Yajra\DataTables\DataTables;


class ClienteController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-cliente|editar-cliente|crear-cliente|eliminar-cliente', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-cliente', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-cliente', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-cliente', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-cliente', ['only' => ['showrestore','restaurar']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $clientes = DB::table('clientes as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.telefono',
                    'c.ruc',
                    'c.email',
                    'c.direccion',
                )->where('c.status', '=', 0);
            return DataTables::of($clientes)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($clientes) {
                    return view('admin.cliente.botones', compact('clientes'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.cliente.index');
    }

    public function create()
    {
        return view('admin.cliente.create');
    }

    public function store(ClienteFormRequest $request)
    {
        $validatedData = $request->validated();

        $cliente = new Cliente;
        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->status = '0';
        $cliente->save();

        return redirect('admin/cliente')->with('message', 'Cliente Agregado Satisfactoriamente');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.cliente.edit', compact('cliente'));
    }

    public function update(ClienteFormRequest $request, $cliente)
    {
        $validatedData = $request->validated();

        $cliente = Cliente::findOrFail($cliente);

        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->status =  '0';
        $cliente->update();

        return redirect('admin/cliente')->with('message', 'Cliente Actualizado Satisfactoriamente');
    }

    public function show($id)
    {
        $cliente = DB::table('clientes as c')

            ->select('c.nombre', 'c.ruc', 'c.direccion', 'c.telefono', 'c.email')
            ->where('c.id', '=', $id)->first();

        return  $cliente;
    }
    public function destroy(int $cliente_id)
    {
        $cliente = Cliente::find($cliente_id);
        if ($cliente) {
            $ingreso = Ingreso::all()->where('cliente_id', '=', $cliente_id);
            $venta = Venta::all()->where('cliente_id', '=', $cliente_id);
            $cotizacion = Cotizacion::all()->where('cliente_id', '=', $cliente_id);
            if (count($venta) == 0 && count($ingreso) == 0   && count($cotizacion) == 0) {
                if ($cliente->delete()) {

                    return "1";
                } else {
                    return "0";
                }
            } else {
                $cliente->status = 1;
                if ($cliente->update()) {

                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }

    public function showrestore()
    {
        $empresas   = DB::table('clientes as c')
            ->where('c.status', '=', 1)
            ->select(
                'c.id',
                'c.nombre',
                'C.ruc',
                'C.telefono',
                'C.email',
                'C.direccion',
            )->get();


        return $empresas->values()->all();
    }

    public function restaurar($idregistro)
    {
        $registro = Cliente::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
