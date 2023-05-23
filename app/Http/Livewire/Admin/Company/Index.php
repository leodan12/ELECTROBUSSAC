<?php

namespace App\Http\Livewire\Admin\Company;


use Livewire\Component;
use App\Models\Company;
use App\Models\Ingreso;
use App\Models\Venta;
use App\Models\Detalleinventario;
use App\Models\Cotizacion;
use Livewire\WithPagination;

use Illuminate\Support\Facades\File;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $company_id;

    public function deleteCompany($company_id)
    {
        $this->company_id = $company_id;
    }

    public function destroyCompany()
    {
        $company = Company::find($this->company_id);
        $company2 =$company;
        $detalleinventario = Detalleinventario::all()->where('company_id','=',$this->company_id);  
        $ingreso = Ingreso::all()->where('company_id','=',$this->company_id); 
        $venta = Venta::all()->where('company_id','=',$this->company_id); 
        $cotizacion = Cotizacion::all()->where('company_id','=',$this->company_id); 
        if(count($venta)==0 && count($ingreso)==0 && count($detalleinventario)==0 && count($cotizacion)==0){ 
            if( $company->delete()){
        $path = public_path('logos/' . $company2->logo);
            if (File::exists($path)) {   File::delete($path);   
            }
            session()->flash('message','Compañia Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }
 
        }else{  
            $company->status = 1;
            $company->update();
            session()->flash('message','Compañia Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }
       
    }

    public function render()
    {
        
        $companies = Company::orderBy('id','DESC')->where('status','=',0)->paginate(10);
        return view('livewire.admin.company.index',['companies' => $companies]);
    }
}
