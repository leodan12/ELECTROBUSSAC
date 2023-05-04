<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $product_id;

    public function deleteProduct($product_id)
    {
        $this->product_id = $product_id;
    }

    public function destroyProduct()
    {
        $product = Product::find($this->product_id);
        $product->delete();
        session()->flash('message','Producto Eliminado');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $products = Product::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.product.index',['products' => $products]);
    }
}
