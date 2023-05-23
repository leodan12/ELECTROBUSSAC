<?php

namespace App\Http\Livewire\Admin\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;
use App\Models\Ingreso;
use App\Models\Venta;
use App\Models\Cotizacion;
use App\Models\Inventario;


class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $cliente_id;

    public function deleteCliente($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    public function destroyCliente()
    {
        $cliente = Cliente::find($this->cliente_id);
        $ingreso = Ingreso::all()->where('cliente_id','=',$this->cliente_id);   
        $venta = Venta::all()->where('cliente_id','=',$this->cliente_id);  
        $cotizacion = Cotizacion::all()->where('cliente_id','=',$this->cliente_id); 
        if(count($venta)==0 && count($ingreso)==0  && count($cotizacion)==0){ 
            if( $cliente->delete()){
            session()->flash('message','Cliente o Proveedor Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }
 
        }else{  
            $cliente->status = 1;
            $cliente->update();
            session()->flash('message','Cliente o Proveedor Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }

    }
    
    public function render()
    {
        $clientes = Cliente::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.cliente.index',['clientes' => $clientes]);
    }
}
