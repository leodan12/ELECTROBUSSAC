<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyFormRequest;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use App\Models\Ingreso;
use App\Models\Venta;
use App\Models\Detalleinventario;
use App\Models\Cotizacion;
use App\Traits\HistorialTrait;

class CompanyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-empresa|editar-empresa|crear-empresa|eliminar-empresa', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-empresa', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-empresa', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-empresa', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-empresa', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    public function index(Request $request)
    {
        $datoseliminados = DB::table('companies as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();

        if ($request->ajax()) {

            $empresas = DB::table('companies as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.ruc',
                    'c.telefono',
                )->where('c.status', '=', 0);
            return DataTables::of($empresas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($empresas) {
                    return view('admin.company.botones', compact('empresas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.company.index', compact('datoseliminados'));
    }

    public function create()
    {
        return view('admin.company.create');
    }

    public function store(CompanyFormRequest $request)
    {
        $validatedData = $request->validated();

        $company = new Company;
        $company->nombre = $validatedData['nombre'];
        $company->ruc = $validatedData['ruc'];
        $company->direccion = $request->direccion;
        $company->telefono = $request->telefono;
        $company->email = $request->email;

        $company->tipocuentasoles = $request->tipocuentasoles;
        $company->numerocuentasoles = $request->numerocuentasoles;
        $company->ccisoles = $request->ccisoles;
        $company->tipocuentadolares = $request->tipocuentadolares;
        $company->numerocuentadolares = $request->numerocuentadolares;
        $company->ccidolares = $request->ccidolares;

        $company->status = '0';

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {

            $imagen = $request->file('logo');
            $nombreimagen = "logo" . Str::slug($validatedData['nombre']) . "." . $imagen->guessExtension();
            $ruta = public_path("logos");
            if ($imagen->move($ruta, $nombreimagen)) {
                $company->logo = $nombreimagen;
            }
        }
        $company->save();
        $this->crearhistorial('crear', $company->id, $company->nombre, null, 'empresas');
        return redirect('admin/company')->with('message', 'Compañia Agregada Satisfactoriamente');
    }

    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company'));
    }

    public function update(CompanyFormRequest $request, $company)
    {
        $validatedData = $request->validated();
        $company = Company::findOrFail($company);
        $company->nombre = $validatedData['nombre'];
        $company->ruc = $validatedData['ruc'];
        $company->direccion = $request->direccion;
        $company->telefono = $request->telefono;
        $company->email = $request->email;

        $company->tipocuentasoles = $request->tipocuentasoles;
        $company->numerocuentasoles = $request->numerocuentasoles;
        $company->ccisoles = $request->ccisoles;
        $company->tipocuentadolares = $request->tipocuentadolares;
        $company->numerocuentadolares = $request->numerocuentadolares;
        $company->ccidolares = $request->ccidolares;

        $company->status =  '0';
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $imagen = $request->file('logo');
            $path = public_path('logos/' . $company->logo);
            if (File::exists($path)) {
                File::delete($path);
            }
            $nombreimagen = "logo" . Str::slug($validatedData['nombre']) . "." . $imagen->guessExtension();
            $ruta = public_path("logos");
            if ($imagen->move($ruta, $nombreimagen)) {
                $company->logo = $nombreimagen;
            }
        }
        $company->update();
        $this->crearhistorial('editar', $company->id, $company->nombre, null, 'empresas');
        return redirect('admin/company')->with('message', 'Compañia Actualizado Satisfactoriamente');
    }

    public function show($id)
    {
        $company = DB::table('companies as c')

            ->select(
                'c.nombre',
                'c.ruc',
                'c.direccion',
                'c.telefono',
                'c.email',
                'c.logo',
                'c.tipocuentasoles',
                'c.numerocuentasoles',
                'c.ccisoles',
                'c.tipocuentadolares',
                'c.numerocuentadolares',
                'c.ccidolares'
            )
            ->where('c.id', '=', $id)->first();

        return  $company;
    }
    public function destroy(int $idempresa)
    {
        $company = Company::find($idempresa);
        if ($company) {
            $company2 = $company;
            $detalleinventario = Detalleinventario::all()->where('company_id', '=', $idempresa);
            $ingreso = Ingreso::all()->where('company_id', '=', $idempresa);
            $venta = Venta::all()->where('company_id', '=', $idempresa);
            $cotizacion = Cotizacion::all()->where('company_id', '=', $idempresa);
            if (count($venta) == 0 && count($ingreso) == 0 && count($detalleinventario) == 0 && count($cotizacion) == 0) {
                if ($company->delete()) {
                    $path = public_path('logos/' . $company2->logo);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $this->crearhistorial('eliminar', $company->id, $company->nombre, null, 'empresas');
                    return "1";
                } else {
                    return "0";
                }
            } else {
                $company->status = 1;
                if ($company->update()) {
                    $this->crearhistorial('eliminar', $company->id, $company->nombre, null, 'empresas');
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
        $empresas   = DB::table('companies as c')
            ->where('c.status', '=', 1)
            ->select(
                'c.id',
                'c.nombre',
                'C.ruc',
                'C.telefono',
            )->get();


        return $empresas->values()->all();
    }

    public function restaurar($idregistro)
    {
        $registro = Company::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $registro->nombre, null, 'empresas');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
