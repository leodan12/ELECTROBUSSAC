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

class DetallekitController extends Controller
{
    public function index()
    { 
        $kits = Product::all()
        ->where('status','=',0)
        ->where('tipo','=','kit');
        return view('admin.kit.index', compact('kits'));
    }

    public function create()
    {
        $categories = Category::all()->where('status','=',0);
        $products = Product::all()
        ->where('status','=',0)
        ->where('tipo','=','estandar');
        return view('admin.kit.create',compact('categories','products'));
    }

    public function store( Request $request)
    {
  
        $producto = new Product;
        $producto->category_id =$request->category_id;
        $producto->nombre = $request->nombre;
        $producto->codigo =$request->codigo;
        $producto->unidad ="unidad";
        $producto->und ="unidad";
        $producto->tipo ="kit";
        $producto->unico =0;
        $producto->maximo =$request->NoIGV;
        $producto->minimo =$request->NoIGV;
        $producto->moneda =$request->moneda;
        $producto->NoIGV =$request->NoIGV;
        $producto->SiIGV =$request->SiIGV;
        $producto->status =$request->status == true ? '1':'0';
         
        if (  $producto->save() ) {
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
            return redirect('admin/kits')->with('message','Kit de Productos Agregado Satisfactoriamente');
        }else{
            return redirect('admin/kits')->with('message','No se pudo agregar el kit');
        }
         
        
    }

    public function show($id)
    {
        $product=DB::table('products as p')
        ->join('categories as c','p.category_id','=','c.id')
        ->join('kits as k','k.product_id','=','p.id')
        ->join('products as pk','k.kitproduct_id','=','pk.id')
        ->select('p.maximo','p.minimo','c.nombre as nombrecategoria','p.nombre','p.codigo','p.unidad','p.und',
        'p.moneda','p.NoIGV','p.SiIGV','k.kitproduct_id as idkitproduct','pk.nombre as kitproductname','pk.maximo as kitproductmaximo'
        ,'pk.minimo as kitproductminimo','pk.codigo as kitproductcodigo','pk.unidad as kitproductunidad','pk.und as kitproductund'
        ,'pk.moneda as kitproductmoneda','pk.NoIGV as kitproductnoigv','pk.SiIGV as kitproductsiigv'
        ,'k.cantidad as kitcantidad','k.preciounitario as kitpreciounitario','k.preciounitariomo as kitpreciounitariomo'
        ,'k.preciofinal as kitpreciofinal')
        ->where('p.id','=',$id)->get() ;
        
        return  $product;
    }
    public function destroy(int $kit_id)
    {
        $product = Product::find($kit_id);

        $inventario = Inventario::all()->where('product_id','=',$kit_id); 
        $ingreso = Detalleingreso::all()->where('product_id','=',$kit_id); 
        $venta = Detalleventa::all()->where('product_id','=',$kit_id); 
        $cotizacion = Detallecotizacion::all()->where('product_id','=',$kit_id); 

        if(count($inventario)==0 && count($ingreso)==0 && count($venta)==0 && count($cotizacion)==0 ){ 
            $product->delete(); 
        }else{  
            $product->status = 1;
            $product->update(); 
        }
        return redirect()->back()->with('message','Kit de Productos Eliminado');
        $this->dispatchBrowserEvent('close-modal');
        
     }

}
