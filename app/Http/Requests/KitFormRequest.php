<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class KitFormRequest extends FormRequest
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
            'codigo' => [
                'nullable',
                Rule::unique('products')->ignore($this->route('product')),
            ],
            'moneda' => [
                'required',
                'string'
            ],
            'preciocompra' => [
                'required',
                'min:0',
            ],
            'NoIGV' => [
                'required',
                'min:0',

            ],
            'SiIGV' => [
                'required',
                'min:0',

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
            'nombre.required' => 'Agrega el Nombre del Kit.',
            'moneda.required' => 'Agrega el Tipo de Moneda del Kit.',
            'NoIGV.required' => 'Agregar el Precio Sin IGV del Kit.',
            'SiIGV.required' => 'Agregar el Precio Con IGV del Kit.',
            'nombre.unique' => 'El Nombre del Kit ya ha sido registrada.',
            'NoIGV.min' => 'El Precio minimo sin IGV  debe ser mayor a 0.',
            'SiIGV.min' => 'El Precio minimo con IGV debe ser mayor a 0.',
            'codigo.unique' => 'El Codigo ya esta registrado.',
        ];
    }
}
