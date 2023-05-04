<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClienteFormRequest extends FormRequest
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
            'nombre' => [
                'required',
                Rule::unique('clientes')->ignore($this->route('cliente')),
                'string'
            ],
            'ruc' => [
                'required',
                Rule::unique('clientes')->ignore($this->route('cliente')),
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'Agrega el Nombre del Cliente.',
            'ruc.required' => 'Agrega el Ruc del Cliente.',
            'nombre.unique' => 'El Nombre del Cliente ya ha sido registrada.',
            'ruc.unique' => 'El Ruc del Cliente ya ha sido registrado.',  
        ];
    }
}
