<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    public function index()
    {
        return view('admin.company.index');
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
}
