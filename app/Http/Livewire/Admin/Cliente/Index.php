<?php

namespace App\Http\Livewire\Admin\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;

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
        $cliente->delete();
        session()->flash('message','Proveedor o Cliente Eliminado');
        $this->dispatchBrowserEvent('close-modal');
    }
    
    public function render()
    {
        $clientes = Cliente::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.cliente.index',['clientes' => $clientes]);
    }
}
