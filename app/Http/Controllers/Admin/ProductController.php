<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventario;
use App\Models\Detalleingreso;
use App\Models\Detalleventa;
use App\Models\Detallecotizacion;
use App\Models\Detalleinventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-producto|editar-producto|crear-producto|eliminar-producto', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-producto', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    public function index(Request $request)
    {
        $datoseliminados = DB::table('products as c')
            ->where('c.status', '=', 1)
            ->where('c.tipo', '=', 'estandar')
            ->select('c.id')
            ->count();
        if ($request->ajax()) {

            $productos = DB::table('products as p')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->select(
                    'p.id',
                    'c.nombre as categoria',
                    'p.nombre',
                    'p.codigo',
                    'p.unidad',
                    'p.moneda',
                    'p.NoIGV',
                    'p.SiIGV',
                )->where('p.status', '=', 0)
                ->where('p.tipo', '=', 'estandar');
            return DataTables::of($productos)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($productos) {
                    return view('admin.products.botones', compact('productos'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.products.index', compact('datoseliminados'));
    }

    public function create()
    {
        $categories = Category::all()->where('status', '=', 0);
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($validatedData['category_id']);

        $product = new Product;
        $product->category_id = $validatedData['category_id'];
        $product->nombre = $validatedData['nombre'];
        $product->codigo = $request->codigo;
        $product->unidad = $validatedData['unidad'];
        // $product->und = $request->und;
        $product->tipo = "estandar";
        $product->unico = 0;
        $product->maximo = $validatedData['NoIGV'];
        $product->minimo = $validatedData['NoIGV'];
        $product->moneda = $validatedData['moneda'];
        $product->NoIGV = $validatedData['NoIGV'];
        $product->SiIGV = $validatedData['SiIGV'];
        $product->status =  '0';
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $product->cantidad2 = $request->cantidad2;
            $product->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $product->cantidad3 = $request->cantidad3;
                $product->precio3 = $request->precio3;
            }
        }
        
        $product->save();
        $inventario = new Inventario;
        $inventario->product_id = $product->id;
        $inventario->stockminimo = 5;
        $inventario->stocktotal = 0;
        $inventario->status = 0;

        $inventario->save();
        $this->crearhistorial('crear', $inventario->id, $product->nombre, null, 'inventarios');
        $this->crearhistorial('crear', $product->id, $product->nombre, null, 'productos');
        return redirect('admin/products')->with('message', 'Producto Agregado Satisfactoriamente');
    }

    public function edit(int $product_id)
    {
        $lascategorias = Category::all()->where('status', '=', 0);
        $product = Product::findOrFail($product_id);
        $micategoria = Category::all()
            ->where('id', '=', $product->category_id)
            ->where('status', '=', 1);
        if ($micategoria) {
            $categories = $lascategorias->concat($micategoria);
        }
        return view('admin.products.edit', compact('categories', 'product'));
    }

    public function update(ProductFormRequest $request, int $product_id)
    {
        $validatedData = $request->validated();
        $categoria = Product::findOrFail($product_id);
        $product = Category::findOrFail($categoria->category_id)
            ->products()->where('id', $product_id)->first();
        if ($product) {
            $product->category_id = $validatedData['category_id'];
            $product->nombre = $validatedData['nombre'];
            $product->codigo = $request->codigo;
            $product->unidad = $validatedData['unidad'];
            // $product->und = $request->und;
            $product->tipo = "estandar";
            $product->unico = 0;
            $product->maximo = $validatedData['maximo'];
            $product->minimo = $validatedData['minimo'];
            $product->moneda = $validatedData['moneda'];
            $product->NoIGV = $validatedData['NoIGV'];
            $product->SiIGV = $validatedData['SiIGV'];
            $product->status =  '0';
            if ($request->cantidad2 != null && $request->precio2 != null) {
                $product->cantidad2 = $request->cantidad2;
                $product->precio2 = $request->precio2;
                if ($request->cantidad3 != null && $request->precio3 != null) {
                    $product->cantidad3 = $request->cantidad3;
                    $product->precio3 = $request->precio3;
                }
            } else {
                $product->cantidad2 = null;
                $product->precio2 =  null;
            }
            if ($request->cantidad3 == null || $request->precio3 == null) {
                $product->cantidad3 = null;
                $product->precio3 =  null;
            }
            $product->update();
            $this->crearhistorial('editar', $product->id, $product->nombre, null, 'productos');
            return redirect('/admin/products')->with('message', 'Producto Actualizado Satisfactoriamente');
        } else {
            return redirect('admin/products')->with('message', 'No se encontro el ID del Producto');
        }
    }

    public function destroy(int $product_id)
    {
        $product = Product::find($product_id);
        if ($product) {
            $inventario = "";
            $inv = Inventario::all()->where('product_id', '=', $product_id)->first();
            if ($inv) {
                $inventario = Detalleinventario::all()->where('inventario_id', '=', $inv->id);
            } else {
                $inventario = Inventario::all()->where('product_id', '=', $product_id);
            }
            $ingreso = Detalleingreso::all()->where('product_id', '=', $product_id);
            $venta = Detalleventa::all()->where('product_id', '=', $product_id);
            $cotizacion = Detallecotizacion::all()->where('product_id', '=', $product_id);

            if (count($inventario) == 0 && count($ingreso) == 0 && count($venta) == 0  && count($cotizacion) == 0) {
                if ($product->delete()) {
                    $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'productos');
                    return "1";
                } else {
                    return "0";
                }
            } else {
                $product->status = 1;
                if ($product->update()) {
                    $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'productos');
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    public function show($id)
    {
        $product = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'p.maximo',
                'p.minimo',
                'c.nombre as nombrecategoria',
                'p.tipo',
                'p.nombre',
                'p.codigo',
                'p.unidad',
                //'p.und',
                'p.moneda',
                'p.NoIGV',
                'p.SiIGV',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3'
            )
            ->where('p.id', '=', $id)->first();

        return  $product;
    }

    public function showrestore()
    {
        $productos   = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.status', '=', 1)
            ->where('p.tipo', '=', 'estandar')
            ->select(
                'p.id',
                'c.nombre as categoria',
                'p.nombre',
                'p.codigo',
                'p.unidad',
                'p.moneda',
                'p.NoIGV',
                'p.SiIGV',
            )->get();


        return $productos->values()->all();
    }

    public function restaurar($idregistro)
    {
        $producto = Product::find($idregistro);
        if ($producto) {
            $producto->status = 0;
            if ($producto->update()) {
                $this->crearhistorial('restaurar', $producto->id, $producto->nombre, null, 'productos');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
