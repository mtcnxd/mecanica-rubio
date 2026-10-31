<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceUpdateRequest extends FormRequest
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
            "odometer"   => 'nullable',
            "entry_date" => 'required',
            "total"      => 'required',
            "notes"      => 'nullable',
            "status"     => 'required',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
