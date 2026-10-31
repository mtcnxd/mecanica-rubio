<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
            'client_id'     => 'required',
            'car_id'        => 'required',
            'odometer'      => 'nullable',
            'fault'         => 'required|min:10',
            'service_type'  => 'required',
            'comments'      => 'nullable',
            'quote'         => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required'    => 'El cliente es requerido',
            'car_id.required'       => 'El auto es requerido',
            'fault.required'        => 'La descripcion es un campo requerido',
            'fault.min'             => 'La descripcion debe contener por lo menos 10 caracteres',
            'service_type.required' => 'Se requiere indicar el tipo de servicio',
        ];
    }
}
