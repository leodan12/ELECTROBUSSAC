<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngresoFormRequest extends FormRequest
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
            'company_id' => [
                'required',
                'string'
            ],

            'cliente_id' => [
                'required',
                'string'
            ],

            'fecha' => [
                'required',
            ],

            'moneda' => [
                'required',
                
                'string'
            ],
            'formapago' => [
                'required', 
                'string'
            ],

            'costoventa' => [
                'required',
            ],
             
            'observacion' => [
                'nullable',
            ],
            'tasacambio' => [
                'nullable',
            ],
            'fechav' => [
                'nullable',
            ],
            'pagada' => [
                'required', 
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Seleccionar Empresa.',
            'cliente_id.unique' => 'Seleccionar el Cliente.',  
        ];
    }
}
