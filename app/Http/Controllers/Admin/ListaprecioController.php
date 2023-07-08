<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorialTrait;
use Yajra\DataTables\DataTables;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\Listaprecio;

class ListaprecioController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-lista-precios|editar-lista-precios|crear-lista-precios|eliminar-lista-precios', ['only' => ['index']]);
        $this->middleware('permission:crear-lista-precios', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-lista-precios', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-lista-precios', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $listaprecios = DB::table('listaprecios as lp')
                ->join('products as p', 'lp.product_id', '=', 'p.id')
                ->join('clientes as c', 'lp.cliente_id', '=', 'c.id')
                ->select(
                    'lp.id',
                    'lp.moneda',
                    'p.nombre as producto',
                    'lp.preciounitariomo',
                    'c.nombre as cliente',
                );
            return DataTables::of($listaprecios)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($listaprecios) {
                    return view('admin.listaprecio.botones', compact('listaprecios'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.listaprecio.index');
    }
    //vista crear
    public function create()
    {
        $product = Product::all()->where('status', '=', 0);
        $cliente = Cliente::all()->where('status', '=', 0);
        return view('admin.listaprecio.create', compact('product', 'cliente'));
    }
    //funcion para guardar un registro
    public function store(Request $request)
    {   //validamos los datos y guardamos el registro
        $this->validate($request, [
            'cliente_id' => 'required',
            'product_id' => 'required',
            'moneda' => 'required',
            'preciounitariomo' => 'required',
        ]);
        $listaprecio =  new Listaprecio;
        $listaprecio->cliente_id = $request->cliente_id;
        $listaprecio->product_id = $request->product_id;
        $listaprecio->moneda = $request->moneda;
        $listaprecio->preciounitariomo = $request->preciounitariomo;
        $listaprecio->save();
        $prod = Product::find($listaprecio->product_id);
        $cli = Cliente::find($listaprecio->cliente_id);
        $this->crearhistorial('crear', $listaprecio->id, $prod->nombre, $cli->nombre,  'listaprecios');
        return redirect('/admin/listaprecios')->with('message', 'Precio Agregado Satisfactoriamente');
    }
    //funcion para obtener los clientes con precio para un producto
    public function clientesxproducto($id)
    {
        $listaclientes = DB::table('clientes as c')
            ->join('listaprecios as lp', 'lp.cliente_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('lp.product_id', '=', $id)
            ->get();
        return $listaclientes;
    }
    // vista editar
    public function edit($id)
    {
        $precio = Listaprecio::find($id);
        $product = Product::all()->where('id', '=', $precio->product_id);
        $cliente = Cliente::all()->where('id', '=', $precio->cliente_id);
        return view('admin.listaprecio.edit', compact('product', 'cliente', 'precio'));
    }
    //funcion para actualizar un registro
    public function update(Request $request, $id)
    {   //validamos los datos y actualizamos el registro
        $this->validate($request, [
            'cliente_id' => 'required',
            'product_id' => 'required',
            'moneda' => 'required',
            'preciounitariomo' => 'required',
        ]);
        $listaprecio =  Listaprecio::find($id);
        $listaprecio->cliente_id = $request->cliente_id;
        $listaprecio->product_id = $request->product_id;
        $listaprecio->moneda = $request->moneda;
        $listaprecio->preciounitariomo = $request->preciounitariomo;
        $listaprecio->update();
        $prod = Product::find($listaprecio->product_id);
        $cli = Cliente::find($listaprecio->cliente_id);
        $this->crearhistorial('editar', $listaprecio->id, $prod->nombre, $cli->nombre,  'listaprecios');
        return redirect('/admin/listaprecios')->with('message', 'Precio Actualizado Satisfactoriamente');
    }
    //funcion para ver el registro de un precio
    public function show($id)
    {
        $product = DB::table('listaprecios as lp')
            ->join('products as p', 'lp.product_id', '=', 'p.id')
            ->join('clientes as c', 'lp.cliente_id', '=', 'c.id')
            ->select(
                'lp.id',
                'p.nombre as producto',
                'c.nombre as cliente',
                'lp.moneda',
                'lp.preciounitariomo'
            )
            ->where('lp.id', '=', $id)->first();
        return  $product;
    }
    //funcion para eliminar un registro
    public function destroy(int $idlista)
    {
        $listaprecio = Listaprecio::find($idlista);
        if ($listaprecio) {
            $prod = Product::find($listaprecio->product_id);
            $cli = Cliente::find($listaprecio->cliente_id);
            if ($listaprecio->delete()) {
                $this->crearhistorial('eliminar', $listaprecio->id, $prod->nombre, $cli->nombre, 'listaprecios');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
