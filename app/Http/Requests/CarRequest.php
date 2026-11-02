<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    
    public function rules(): array
    {
        return [
            'client_id' => 'required',
            'brand'     => 'required',
            'model'     => 'required',
            'year'      => 'required',
            'serie'     => 'required',
            'plate'     => 'nullable',
            'color'     => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'brand.required'     => 'La marca es requerida',
            'model.required'     => 'El modelo es requerido',
            'client_id.required' => 'El cliente es requerido',
            'year.required'      => 'El año es requerido',
            'serie.required'     => 'El VIN es requerido',
        ];
    }
}
