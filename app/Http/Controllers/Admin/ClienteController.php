<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteFormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Ingreso;
use App\Models\Venta;


class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all()->where('status','=',0);
        //$clientes = Cliente::orderBy('id', 'asc')->get();
        return view('admin.cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.cliente.create');
    }

    public function store(ClienteFormRequest $request)
    {
        $validatedData = $request->validated();

        $cliente = new Cliente;
        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono= $request->telefono;
        $cliente->email = $request->email;
        $cliente->status = $request->status == true ? '1':'0';
        $cliente->save();

        return redirect('admin/cliente')->with('message','Cliente Agregado Satisfactoriamente');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.cliente.edit', compact('cliente'));
    }

    public function update(ClienteFormRequest $request,$cliente)
    {
        $validatedData = $request->validated();

        $cliente = Cliente::findOrFail($cliente);

        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono= $request->telefono;
        $cliente->email = $request->email;
        $cliente->status = $request->status == true ? '1':'0';
        $cliente->update();

        return redirect('admin/cliente')->with('message','Cliente Actualizado Satisfactoriamente');
    }

    public function show($id)
    {
        $cliente=DB::table('clientes as c')
        
        ->select('c.nombre','c.ruc','c.direccion','c.telefono','c.email')
        ->where('c.id','=',$id)->first() ;
        
            return  $cliente ;
    }
    public function destroy(int $cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        $ingreso = Ingreso::all()->where('cliente_id','=',$cliente_id); 
        $venta = Venta::all()->where('cliente_id','=',$cliente_id); 
        if(count($venta)==0 && count($ingreso)==0){ 
            if( $cliente->delete()){
                return redirect()->back()->with('message','Cliente Eliminado');
        } 
        }else{  
            $cliente->status = 1;
            $cliente->update();
            return redirect()->back()->with('message','Cliente Eliminado');
        }
          
     }
}
