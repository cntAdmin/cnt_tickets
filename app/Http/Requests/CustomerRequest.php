<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cif' => ['required', 'string', 'max:15'],
            'name' => ['required', 'string', 'max:150'],
            'alias' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string'],
            'postcode' => ['nullable', 'string', 'max:5'],
            'phone' => ['nullable', 'string'],
            // 'fax' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
