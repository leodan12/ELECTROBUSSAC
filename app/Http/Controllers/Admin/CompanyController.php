<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyFormRequest;
use Illuminate\Support\Facades\DB;

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
        $company->telefono= $request->telefono;
        $company->email = $request->email;
        $company->status = $request->status == true ? '1':'0';
        $company->save();

        return redirect('admin/company')->with('message','Proveedor Agregado Satisfactoriamente');
    }

    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company'));
    }

    public function update(CompanyFormRequest $request,$company)
    {
        $validatedData = $request->validated();

        $company = Company::findOrFail($company);

        $company->nombre = $validatedData['nombre'];
        $company->ruc = $validatedData['ruc'];
        $company->direccion = $request->direccion;
        $company->telefono= $request->telefono;
        $company->email = $request->email;
        $company->status = $request->status == true ? '1':'0';
        $company->update();

        return redirect('admin/company')->with('message','Proveedor Actualizado Satisfactoriamente');
    }

    public function show($id)
    {
        $company=DB::table('companies as c')
        
        ->select('c.nombre','c.ruc','c.direccion','c.telefono','c.email')
        ->where('c.id','=',$id)->first() ;
        
            return  $company ;
    }
}
