<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
                Rule::unique('companies')->ignore($this->route('company')),
                'string'
            ],
            'ruc' => [
                'required',
                Rule::unique('companies')->ignore($this->route('company')),
                'string'
            ],
            'logo' => [
                'nullable',
                'image' ,
                'file'
            ],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'Agrega el Nombre del Proveedor.',
            'ruc.required' => 'Agrega el Ruc del Proveedor.',
            'nombre.unique' => 'El Nombre del Proveedor ya ha sido registrado.',
            'ruc.unique' => 'El Ruc del Proveedor ya ha sido registrado.',  
        ];
    }
}
