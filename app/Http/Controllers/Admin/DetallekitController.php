<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Kit;
use App\Models\Category;
use App\Models\Inventario;
use App\Models\Detalleingreso;
use App\Models\Detalleventa;
use App\Models\Detallecotizacion;
use Illuminate\Http\Request;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class DetallekitController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-kit|editar-kit|crear-kit|eliminar-kit', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-kit', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-kit', ['only' => ['edit', 'update', 'destroydetallekit']]);
        $this->middleware('permission:eliminar-kit', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-kit', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    public function index(Request $request)
    {
        $datoseliminados = DB::table('products as c')
            ->where('c.status', '=', 1)
            ->where('c.tipo', '=', 'kit')
            ->select('c.id')
            ->count();

        if ($request->ajax()) {

            $kits = DB::table('products as p')
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
                ->where('p.tipo', '=', 'kit');
            return DataTables::of($kits)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($kits) {
                    return view('admin.kit.botones', compact('kits'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.kit.index', compact('datoseliminados'));
    }

    public function create()
    {
        $categories = Category::all()->where('status', '=', 0);
        $products = Product::all()
            ->where('status', '=', 0)
            ->where('tipo', '=', 'estandar');
        return view('admin.kit.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {

        $producto = new Product;
        $producto->category_id = $request->category_id;
        $producto->nombre = $request->nombre;
        $producto->codigo = $request->codigo;
        $producto->unidad = "unidad";
        // $producto->und = "unidad";
        $producto->tipo = "kit";
        $producto->unico = 0;
        $producto->maximo = $request->NoIGV;
        $producto->minimo = $request->NoIGV;
        $producto->moneda = $request->moneda;
        $producto->tasacambio = $request->tasacambio;
        $producto->NoIGV = $request->NoIGV;
        $producto->SiIGV = $request->SiIGV;
        $producto->status =  '0';
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $producto->cantidad2 = $request->cantidad2;
            $producto->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $producto->cantidad3 = $request->cantidad3;
                $producto->precio3 = $request->precio3;
            }
        }

        if ($producto->save()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detallekit = new Kit;
                    $Detallekit->product_id = $producto->id;
                    $Detallekit->kitproduct_id = $product[$i];
                    $Detallekit->cantidad = $cantidad[$i];
                    $Detallekit->preciounitario = $preciounitario[$i];
                    $Detallekit->preciounitariomo = $preciounitariomo[$i];
                    $Detallekit->preciofinal = $preciofinal[$i];
                    $Detallekit->save();
                }
            }
            $this->crearhistorial('crear', $producto->id, $producto->nombre, null, 'kits');
            return redirect('admin/kits')->with('message', 'Kit de Productos Agregado Satisfactoriamente');
        } else {
            return redirect('admin/kits')->with('message', 'No se pudo agregar el kit');
        }
    }

    public function edit(int $kit_id)
    {
        $lascategorias = Category::all()->where('status', '=', 0);
        $product = Product::findOrFail($kit_id);
        $micategoria = Category::all()
            ->where('id', '=', $product->category_id)
            ->where('status', '=', 1);
        if ($micategoria) {
            $categories = $lascategorias->concat($micategoria);
        }
        $products = Product::all()
            ->where('status', '=', 0)
            ->where('tipo', '=', 'estandar');
        $kitdetalles = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->select(
                'p.moneda',
                'k.id',
                'k.product_id',
                'k.kitproduct_id',
                'k.cantidad',
                'k.preciounitario',
                'k.preciounitariomo',
                'k.preciofinal',
                'p.nombre as producto'
            )
            ->where('k.product_id', '=', $kit_id)->get();
        return view('admin.kit.edit', compact('categories', 'product', 'kitdetalles', 'products'));
    }

    public function update(Request $request, int $kit_id)
    {

        $producto = Product::findOrFail($kit_id);
        $producto->category_id = $request->category_id;
        $producto->nombre = $request->nombre;
        $producto->codigo = $request->codigo;
        $producto->unidad = "unidad";
        // $producto->und = "unidad";
        $producto->tipo = "kit";
        $producto->unico = 0;
        $producto->maximo = $request->NoIGV;
        $producto->minimo = $request->NoIGV;
        $producto->moneda = $request->moneda;
        $producto->tasacambio = $request->tasacambio;
        $producto->NoIGV = $request->NoIGV;
        $producto->SiIGV = $request->SiIGV;
        $producto->status =  '0';
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $producto->cantidad2 = $request->cantidad2;
            $producto->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $producto->cantidad3 = $request->cantidad3;
                $producto->precio3 = $request->precio3;
            }
        } else {
            $producto->cantidad2 = null;
            $producto->precio2 =  null;
        }
        if ($request->cantidad3 == null || $request->precio3 == null) {
            $producto->cantidad3 = null;
            $producto->precio3 =  null;
        }
        if ($producto->update()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {

                    $Detallekit = new Kit;
                    $Detallekit->product_id = $producto->id;
                    $Detallekit->kitproduct_id = $product[$i];
                    $Detallekit->cantidad = $cantidad[$i];
                    $Detallekit->preciounitario = $preciounitario[$i];
                    $Detallekit->preciounitariomo = $preciounitariomo[$i];
                    $Detallekit->preciofinal = $preciofinal[$i];
                    $Detallekit->save();
                }
            }
            $this->crearhistorial('editar', $producto->id, $producto->nombre, null, 'kits');
            return redirect('admin/kits')->with('message', 'Kit de Productos Actualizado Satisfactoriamente');
        } else {
            return redirect('admin/kits')->with('message', 'No se pudo agregar el kit');
        }
    }

    public function show($id)
    {

        $product = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select(
                'p.maximo',
                'p.minimo',
                'c.nombre as nombrecategoria',
                'p.nombre',
                'p.codigo',
                'p.unidad',
                //'p.und',
                'p.moneda',
                'p.NoIGV',
                'p.SiIGV',
                'k.kitproduct_id as idkitproduct',
                'pk.nombre as kitproductname',
                'pk.maximo as kitproductmaximo',
                'pk.minimo as kitproductminimo',
                'pk.codigo as kitproductcodigo',
                'pk.unidad as kitproductunidad',
                //'pk.und as kitproductund',
                'pk.moneda as kitproductmoneda',
                'pk.NoIGV as kitproductnoigv',
                'pk.SiIGV as kitproductsiigv',
                'k.cantidad as kitcantidad',
                'k.preciounitario as kitpreciounitario',
                'k.preciounitariomo as kitpreciounitariomo',
                'p.tasacambio',
                'k.preciofinal as kitpreciofinal'
            )
            ->where('p.id', '=', $id)->get();

        return  $product;
    }
    public function destroy(int $kit_id)
    {
        $product = Product::find($kit_id);
        if ($product) {

            $ingreso = Detalleingreso::all()->where('product_id', '=', $kit_id);
            $venta = Detalleventa::all()->where('product_id', '=', $kit_id);
            $cotizacion = Detallecotizacion::all()->where('product_id', '=', $kit_id);

            if (count($ingreso) == 0 && count($venta) == 0 && count($cotizacion) == 0) {
                if ($product->delete()) {
                    $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'kits');
                    return "1";
                } else {
                    return "0";
                }
            } else {
                $product->status = 1;
                if ($product->update()) {
                    $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'kits');
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }

    public function destroydetallekit($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detallekit = Kit::find($id);
        $productoh = DB::table('products as p')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->where('k.id', '=', $id)
            ->select('p.id', 'p.nombre')->first();
        if ($detallekit) {
            $kit = DB::table('kits as dc')
                ->join('products as p', 'dc.product_id', '=', 'p.id')
                ->select('p.id', 'dc.preciofinal', 'p.NoIGV')
                ->where('dc.id', '=', $id)->first();

            if ($detallekit->delete()) {
                $costof = $kit->NoIGV;
                $detalle = $kit->preciofinal;

                $editprod = Product::findOrFail($kit->id);
                $editprod->NoIGV = $costof - $detalle;
                $editprod->SiIGV = round(($costof - $detalle) * 1.18, 2);
                $editprod->update();
                $this->crearhistorial('editar', $productoh->id, $productoh->nombre, null, 'kits');
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function showrestore()
    {
        $productos   = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.status', '=', 1)
            ->where('p.tipo', '=', 'kit')
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
        $registro = Product::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $registro->nombre, null, 'kits');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
