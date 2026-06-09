<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name'  => 'required|string|min:10|max:255',
            'phone' => 'required|string|min:10|max:10',
            'email' => 'nullable|email',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre es requerido',
            'name.min'       => 'El nombre debe tener al menos 10 caracteres',
            'phone.required' => 'El teléfono es requerido',
            'phone.min'      => 'El teléfono debe tener al menos 10 caracteres',
            'email.email'    => 'El correo no tiene un formato valido'
        ];
    }
}
