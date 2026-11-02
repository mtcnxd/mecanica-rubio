<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
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
            'employee_id'   => 'required',
            'name'          => 'required',
            'category'      => 'required',
            'status'        => 'required',
            'amount'        => 'required',
            'price'         => 'required',
            'service_id'    => 'nullable',
            'description'   => 'nullable',
            'attach'        => 'file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'El concepto es requerido',
            'category.required' => 'La categoria es requerida',
            'status.required'   => 'El estado es requerido',
            'amount.required'   => 'El monto es requerido',
            'price.required'    => 'El precio es requerido',
        ];
    }
}
