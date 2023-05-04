<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Product;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Detalleinventario;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventarioFormRequest;
use App\Http\Requests\DetalleInventarioFormRequest;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::all();
        return view('admin.inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $products = Product::all()->where('status','=',0);
        $companies = Company::all();
        return view('admin.inventario.create',compact('products','companies'));
    }

    public function store(InventarioFormRequest $request)
    {
        
        $validatedData = $request->validated();
        $product = Product::findOrFail($validatedData['product_id']);
        $inventario = $product->inventarios()->create([
            'product_id' => $validatedData['product_id'],
            'stockminimo' => $validatedData['stockminimo'],
            'stocktotal' => $validatedData['stocktotal'],
            'status' => $request->status == true ? '1':'0',
        ]);
        if ($inventario) {
            $empresa = $request->Lempresa;
            $stockempresa = $request->Lstockempresa;
            if ($empresa !== null) {
                for ($i = 0; $i < count($empresa); $i++) {

                    $Detalleinventario = new Detalleinventario;
                    $Detalleinventario->inventario_id = $inventario->id;
                    $Detalleinventario->company_id = $empresa[$i];
                    $Detalleinventario->stockempresa = $stockempresa[$i];
                    $Detalleinventario->status = 0;
                    $Detalleinventario->save();
                }
                return redirect('admin/inventario')->with('message','Stok Agregado Satisfactoriamente');
            }
        }

        
       
}

public function edit(int $inventario_id)
    {
        $companies = Company::all();
        //$products = Product::all()->where('status','=',0);
        $products = DB::table('products as p')
        ->join('inventarios as i', 'i.product_id', '=', 'p.id')
        ->select('p.id','p.nombre','p.status')
        ->where('i.id', '=', $inventario_id)
        ->get();
        $inventario = Inventario::findOrFail($inventario_id);
        $detalleinventario = DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->select('di.id as iddetalleinventario','c.nombre', 'di.stockempresa')
            ->where('i.id', '=', $inventario_id)->get();
    
        return view('admin.inventario.edit', compact('products','inventario','companies','detalleinventario'));
    } 

    public function update(Request $request,int $inventario_id)
    {
        $inventario = Inventario::findOrFail($inventario_id);
        $inventario->product_id = $request->product_id;
        $inventario->stockminimo = $request->stockminimo;
        $inventario->stocktotal = $request->stocktotal;
        $inventario->status = $request->status == true ? '1':'0';
        if ($inventario->update()) {
            $empresa = $request->Lempresa;
            $stockempresa = $request->Lstockempresa;
            if ($empresa !== null) {
                for ($i = 0; $i < count($empresa); $i++) {

                    $Detalleinventario = new Detalleinventario;
                    $Detalleinventario->inventario_id = $inventario->id;
                    $Detalleinventario->company_id = $empresa[$i];
                    $Detalleinventario->stockempresa = $stockempresa[$i];
                    $Detalleinventario->status = 0;
                    $Detalleinventario->save();
                }
                
            }
            return redirect('admin/inventario')->with('message','Stock Actualizado Satisfactoriamente');
        }
    }

public function show($id)
    {

        $inventario = DB::table('inventarios as i')
            ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->select(
                'p.nombre',
                'i.stockminimo',
                'i.stocktotal',
                'c.nombre as nombrempresa',
                'di.stockempresa'
                
            )
            ->where('i.id', '=', $id)->get();

        return  $inventario;
    }

    public function destroy(int $inventario_id)
    {
        $inventario = Inventario::findOrFail($inventario_id);
        $inventario->delete();
        return redirect()->back()->with('message','Inventario Eliminado');
     }

    public function destroydetalleinventario($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleinventario = Detalleinventario::find($id);
        $detalleinventario->delete();
    }
}