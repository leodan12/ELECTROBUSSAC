<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    { 
        $products = Product::all()->where('status','=',0);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create',compact('categories'));
    }

    public function store(ProductFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($validatedData['category_id']);
  
        $product = new Product;
        $product->category_id =$validatedData['category_id'];
        $product->nombre = $validatedData['nombre'];
        $product->codigo =$request->codigo;
        $product->unidad =$validatedData['unidad'];
        $product->und =$request->und;
        $product->maximo =$validatedData['NoIGV'];
        $product->minimo =$validatedData['NoIGV'];
        $product->moneda =$validatedData['moneda'];
        $product->NoIGV =$validatedData['NoIGV'];
        $product->SiIGV =$validatedData['SiIGV'];
        $product->status =$request->status == true ? '1':'0';
        $product->save();

        $inventario = new Inventario;
        $inventario->product_id = $product->id;
        $inventario->stockminimo = 5;
        $inventario->stocktotal = 0;
        $inventario->status = 0;
        $inventario->save();
        

        return redirect('admin/products')->with('message','Producto Agregado Satisfactoriamente');
    }

    public function edit(int $product_id)
    {
        $categories = Category::all();
        $product = Product::findOrFail($product_id);
        return view('admin.products.edit', compact('categories','product'));
    } 

    public function update(ProductFormRequest $request,int $product_id)
    {
        $validatedData = $request->validated();
        $categoria = Product::findOrFail($product_id);
        $product = Category::findOrFail($categoria->category_id)
                        ->products()->where('id',$product_id)->first();
        if($product)
        { 
            $product->category_id =$validatedData['category_id'];
            $product->nombre = $validatedData['nombre'];
            $product->codigo =$request->codigo;
            $product->unidad =$validatedData['unidad'];
            $product->und =$request->und;
            $product->maximo =$validatedData['maximo'];
            $product->minimo =$validatedData['minimo'];
            $product->moneda =$validatedData['moneda'];
            $product->NoIGV =$validatedData['NoIGV'];
            $product->SiIGV =$validatedData['SiIGV'];
            $product->status =$request->status == true ? '1':'0';
            $product->update();
            return redirect('/admin/products')->with('message','Producto Actualizado Satisfactoriamente');
        }
        
        else
        {
            return redirect('admin/products')->with('message','No se encontro el ID del Producto');
        }
    }

    public function destroy(int $product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->status=1;
        $product->update();
        return redirect()->back()->with('message','Producto Eliminado');

        
     }
    public function show($id)
    {
        $product=DB::table('products as p')
        ->join('categories as c','p.category_id','=','c.id')
        ->select('p.maximo','p.minimo','c.nombre as nombrecategoria','p.nombre','p.codigo','p.unidad','p.und','p.moneda','p.NoIGV','p.SiIGV')
        ->where('p.id','=',$id)->first() ;
        
            return  $product;
    }

}
