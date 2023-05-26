<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller
{
    public function index()
    {

        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = new Category;
        $category->nombre = $validatedData['nombre'];
        $category->status = '0';
        $category->save();

        return redirect('admin/category')->with('message', 'Categoria Agregada Satisfactoriamente');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, $category)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($category);

        $category->nombre = $validatedData['nombre'];
        $category->status = '0';
        $category->update();

        return redirect('admin/category')->with('message', 'Categoria Actualizada Satisfactoriamente');
    }
}
