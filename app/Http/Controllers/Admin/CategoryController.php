<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-categoria|editar-categoria|crear-categoria|eliminar-categoria', ['only' => ['index']]);
        $this->middleware('permission:crear-categoria', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoria', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-categoria', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-categoria', ['only' => ['showcategoryrestore', 'restaurar']]);
    }

    use HistorialTrait;

    public function index(Request $request)
    {
        $datoseliminados = DB::table('categories as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();
        if ($request->ajax()) {

            $categorias = DB::table('categories as c')
                ->select(
                    'c.id',
                    'c.nombre',
                )->where('c.status', '=', 0);
            return DataTables::of($categorias)
                ->addColumn('accion', 'Accion')
                ->editColumn('accion', function ($categorias) {
                    return view('admin.category.botones', compact('categorias'));
                })
                ->rawColumns(['accion'])
                ->make(true);
        }

        return view('admin.category.index', compact('datoseliminados'));
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(CategoryFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = new Category;
        $category->nombre = $validatedData['nombre'];
        $category->status = '0';
        $category->save();

        $this->crearhistorial('crear', $category->id, $category->nombre, null, 'categorias');

        return redirect('admin/category')->with('message', 'Categoria Agregada Satisfactoriamente');
    }
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }
    public function update(CategoryFormRequest $request, $category)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($category);

        $category->nombre = $validatedData['nombre'];
        $category->status = '0';
        $category->update();

        $this->crearhistorial('editar', $category->id, $category->nombre, null, 'categorias');

        return redirect('admin/category')->with('message', 'Categoria Actualizada Satisfactoriamente');
    }
    public function destroy(int $idcategoria)
    {
        $category = Category::find($idcategoria);
        if ($category) {
            $producto = Product::all()->where('category_id', '=', $category->id);
            if (count($producto) == 0) {
                if ($category->delete()) {
                    $this->crearhistorial('eliminar', $category->id, $category->nombre, null, 'categorias');
                    return "1";
                } else {
                    return "0";
                }
            } else {
                $category->status = 1;
                if ($category->update()) {
                    $this->crearhistorial('eliminar', $category->id, $category->nombre, null, 'categorias');
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    public function showcategoryrestore()
    {
        $categorias =  Category::all()
            ->where('status', '=', 1);


        return $categorias->values()->all();
    }
    public function restaurar($idregistro)
    {
        $categoria = Category::find($idregistro);
        if ($categoria) {
            $categoria->status = 0;
            if ($categoria->update()) {
                $this->crearhistorial('restaurar', $categoria->id, $categoria->nombre, null, 'categorias');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
