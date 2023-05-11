<?php

namespace App\Http\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Models\Product;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $category_id;

    public function deleteCategory($category_id)
    {
        $this->category_id = $category_id;
    }

    public function destroyCategory()
    {
        $category = Category::find($this->category_id);
        $producto = Product::all()->where('category_id','=',$this->category_id); 
        if(count($producto)==0){ 
            $category->delete();
            session()->flash('message','Categoria Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }else{  
            $category->status = 1;
            $category->update();
            session()->flash('message','Categoria Eliminada');
            $this->dispatchBrowserEvent('close-modal');
        }
        
        
    }

    public function render()
    {
        
        $categories = Category::orderBy('id','DESC')->where('status','=',0)->paginate(10);
        return view('livewire.admin.category.index',['categories' => $categories]);
    }
}
