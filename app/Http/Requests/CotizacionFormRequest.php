<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CotizacionFormRequest extends FormRequest
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
            'formapago' => [
                'required',
            ], 
            'moneda' => [
                'required', 
                'string'
            ], 
            'costoventasinigv' => [
                'required',
            ],
            'observacion' => [
                'nullable',
            ],
            'tasacambio' => [
                'nullable',
            ], 
           

        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Seleccionar Empresa.',
            'cliente_id.required' => 'Seleccionar el Cliente.',  
        ];
    }
}
