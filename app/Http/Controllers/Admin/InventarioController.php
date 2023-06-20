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
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class InventarioController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-inventario|editar-inventario|crear-inventario|eliminar-inventario',
            ['only' => ['index', 'show', 'showkits', 'showsinstock']]
        );
        $this->middleware('permission:crear-inventario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-inventario', ['only' => ['edit', 'update', 'destroydetalleinventario']]);
        $this->middleware('permission:eliminar-inventario', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-inventario', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $inventarios = DB::table('inventarios as i')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->select(
                    'i.id',
                    'c.nombre as categoria',
                    'p.nombre as producto',
                    'i.stockminimo',
                    'i.stocktotal',
                )->where('i.status', '=', 0);
            return DataTables::of($inventarios)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($inventarios) {
                    return view('admin.inventario.botones', compact('inventarios'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.inventario.index');
    }
    public function index2()
    {
        return redirect('admin/inventario')->with('verstock', 'Ver');
    }
    public function nroeliminados()
    {
        $datoseliminados = DB::table('inventarios as i')
            ->where('i.status', '=', 1)
            ->select('i.id', 'i.stockminimo', 'i.stockmaximo')
            ->count();
        return $datoseliminados;
    }
    public function numerosinstock()
    {
        $productossinstock = 0;
        $sinstock = Inventario::all()->where('status', '=', 0);
        $sinstock2 =  $sinstock->values()->all();
        for ($i = 0; $i < count($sinstock2); $i++) {
            for ($z = 0; $z < count($sinstock2); $z++) {
                if ($sinstock2[$z]->id == $sinstock2[$i]->id) {
                    if ($sinstock2[$z]->stockminimo >= $sinstock2[$i]->stocktotal) {
                        $productossinstock++;
                    }
                }
            }
        }
        return $productossinstock;
    }
    public function create()
    {
        //$products = Product::all()->where('status','=',0);

        $products = DB::table('products as p')
            ->leftjoin('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select(
                'p.nombre',
                'p.id'
            )
            ->where('i.id', '=', null)
            ->where('p.tipo', '=', "estandar")
            ->get();

        $companies = Company::all();
        return view('admin.inventario.create', compact('products', 'companies'));
    }
    public function store(InventarioFormRequest $request)
    {

        $validatedData = $request->validated();
        $product = Product::findOrFail($validatedData['product_id']);
        $inventario = $product->inventarios()->create([
            'product_id' => $validatedData['product_id'],
            'stockminimo' => $validatedData['stockminimo'],
            'stocktotal' => $validatedData['stocktotal'],
            'status' => '0',
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
                $this->crearhistorial('crear', $inventario->id, $product->nombre, null, 'inventarios');
                return redirect('admin/inventario')->with('message', 'Stok Agregado Satisfactoriamente');
            } else {
                return redirect('admin/inventario')->with('message', 'Stok NO Agregado');
            }
        } else {
            return redirect('admin/inventario')->with('message', 'Stok NO Agregado');
        }
    }
    public function edit(int $inventario_id)
    {
        $companies = DB::table('companies as c')->select('id', 'nombre')->get();


        $products = DB::table('products as p')
            ->join('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre', 'p.status')
            ->where('i.id', '=', $inventario_id)
            ->get();
        $inventario = Inventario::findOrFail($inventario_id);
        $detalleinventario = DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->select('di.id as iddetalleinventario', 'c.nombre', 'di.stockempresa', 'c.id as idcompany')
            ->where('i.id', '=', $inventario_id)->get();

        return view('admin.inventario.edit', compact('products', 'inventario', 'companies', 'detalleinventario'));
    }
    public function update(Request $request, int $inventario_id)
    {
        $inventario = Inventario::findOrFail($inventario_id);
        $inventario->product_id = $request->product_id;
        $inventario->stockminimo = $request->stockminimo;
        $inventario->stocktotal = $request->stocktotal;
        $inventario->status = '0';
        $producth =  DB::table('products as p')
            ->join('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre')
            ->where('i.id', '=', $inventario_id)->first();
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
            $this->crearhistorial('editar', $inventario->id, $producth->nombre, null, 'inventarios');
            return redirect('admin/inventario')->with('message', 'Stock Actualizado Satisfactoriamente');
        } else {
            return redirect('admin/inventario')->with('message', 'Stock NO Actualizado');
        }
    }
    public function show($id)
    {

        $inventario = DB::table('inventarios as i')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->select(
                'p.nombre',
                'i.stockminimo',
                'i.stocktotal'

            )
            ->where('i.id', '=', $id)->get();
        $detalle = DB::table('inventarios as i')
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
        $datos = collect();
        $datos->put('inventario', $inventario);
        if (count($detalle) == 0) {
            $datos->put('haydetalle', "no");
        } else {
            $datos->put('haydetalle', "si");

            $datos->put('detalle', $detalle);
        }


        return  $datos;
    }
    public function showkits()
    {

        $inventario = DB::table('products as p')
            ->select(
                'p.id',
                'p.nombre as kit',
                'p.moneda',
                'p.NoIGV as precio'

            )
            ->where('p.tipo', '=', "kit")->get();

        return  $inventario;
    }
    public function destroy(int $inventario_id)
    {
        $inventario = Inventario::find($inventario_id);
        $producth =  DB::table('products as p')
            ->join('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre')
            ->where('i.id', '=', $inventario_id)->first();
        if ($inventario) {
            $detalle = Detalleinventario::all()->where('inventario_id', '=', $inventario_id);
            if (count($detalle) == 0) {
                if ($inventario->delete()) {
                    $this->crearhistorial('eliminar', $inventario->id, $producth->nombre, null, 'inventarios');
                    return "1";
                } else {
                    return "0";
                }
            } else {
                $inventario->status = 1;
                if ($inventario->update()) {
                    $this->crearhistorial('eliminar', $inventario->id, $producth->nombre, null, 'inventarios');
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    public function destroydetalleinventario($id)
    {
        //buscamos el registro con el id enviado por la URL
        $detalleinventario = Detalleinventario::find($id);
        $inventarioh =  DB::table('inventarios as i')
            ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
            ->select('i.id')
            ->where('di.id', '=', $id)->first();
        $producth =  DB::table('products as p')
            ->join('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre')
            ->where('i.id', '=', $inventarioh->id)->first();
        if ($detalleinventario) {
            $inv = DB::table('detalleinventarios as di')
                ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
                ->select('i.stocktotal', 'di.stockempresa', 'i.id')
                ->where('di.id', '=', $id)->first();
        }
        if ($detalleinventario->delete()) {
            $stocke = $inv->stockempresa;
            $stockt = $inv->stocktotal;
            $idinv = $inv->id;

            $invEdit = Inventario::findOrFail($idinv);
            $invEdit->stocktotal = $stockt - $stocke;
            $invEdit->update();
            $this->crearhistorial('editar', $inventarioh->id, $producth->nombre, null, 'inventarios');
            return 1;
        }
    }
    public function showrestore()
    {
        $empresas = DB::table('inventarios as i')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'i.id',
                'c.nombre as categoria',
                'p.nombre as producto',
                'i.stockminimo',
                'i.stocktotal',
            )->where('i.status', '=', 1)->get();


        return $empresas->values()->all();
    }

    public function restaurar($idregistro)
    {
        $producth =  DB::table('products as p')
            ->join('inventarios as i', 'i.product_id', '=', 'p.id')
            ->select('p.id', 'p.nombre')
            ->where('i.id', '=', $idregistro)->first();

        $registro = Inventario::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $producth->nombre, null, 'inventarios');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }

    public function showsinstock()
    {
        $misinventarios = collect();
        $inventarios = DB::table('inventarios as i')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'i.id',
                'c.nombre as categoria',
                'p.nombre as producto',
                'i.stockminimo',
                'i.stocktotal',
            )->where('i.status', '=', 0)->get();

        for ($i = 0; $i < count($inventarios); $i++) {
            for ($z = 0; $z < count($inventarios); $z++) {
                if ($inventarios[$z]->id == $inventarios[$i]->id) {
                    if ($inventarios[$z]->stockminimo >= $inventarios[$i]->stocktotal) {
                        $inventario = collect();
                        $inventario->put('id', $inventarios[$i]->id);
                        $inventario->put('categoria', $inventarios[$i]->categoria);
                        $inventario->put('producto', $inventarios[$i]->producto);
                        $inventario->put('stockminimo', $inventarios[$i]->stockminimo);
                        $inventario->put('stocktotal', $inventarios[$i]->stocktotal);
                        $misinventarios->push($inventario);
                    }
                }
            }
        }

        return $misinventarios;
    }
}
