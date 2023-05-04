<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventarioFormRequest extends FormRequest
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

            'product_id' => [
                'required',
                Rule::unique('inventarios')->ignore($this->route('inventario')),
                'string'
            ],

            'stockminimo' => [
                'required',
                'integer'
            ],

            'stocktotal' => [
                'required',
                'integer'
            ],
        ];

    }

    public function messages()
    {
        return [
            'product_id.required' => 'Seleccionar el Producto.',
            'stockminimo.required' => 'Agregar el Stock Minimo.',
            'product_id.unique' => 'El Producto ya tiene stock registrado.',  
        ];
    }
}
