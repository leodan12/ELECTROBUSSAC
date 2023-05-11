<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [

            'category_id' => [
                'required',
                'string'
            ],

            'nombre' => [
                'required',
                Rule::unique('products')->ignore($this->route('product')),
                'string'
            ],

            'unidad' => [
                'required',
                'string'
            ],
            'moneda' => [
                'required',
                'string'
            ],
            'NoIGV' => [
                'required',
                
            ],
            'SiIGV' => [
                'required',
                
            ],
            'maximo' => [
                'nullable',
                
            ],
            'minimo' => [
                'nullable',
                
            ],
            
        ];

    }

    public function messages()
    {
        return [
            'nombre.required' => 'Agrega el Nombre del Producto.',
            'unidad.required' => 'Agrega la Unidad del Producto.',
            'moneda.required' => 'Agrega el Tipo de Moneda del Producto.',
            'NoIGV.required' => 'Agregar el Precio Sin IGV del Producto.',
            'SiIGV.required' => 'Agregar el Precio Con IGV del Producto.',
            'nombre.unique' => 'El Nombre del Producto ya ha sido registrada.',  
        ];
    }
}
