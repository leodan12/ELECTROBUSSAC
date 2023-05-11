<?php

namespace App\Http\Livewire\Admin\Company;


use Livewire\Component;
use App\Models\Company;
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
       if( $company->delete())
        $path = public_path('logos/' . $company2->logo);
            if (File::exists($path)) {   File::delete($path);   }

        session()->flash('message','Proveedor o Cliente Eliminada');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        
        $companies = Company::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.company.index',['companies' => $companies]);
    }
}
