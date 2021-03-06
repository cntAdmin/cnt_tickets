<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * Sometimes usar para campos que puede ser que ni se muestren 
     * en el formulario.
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['nullable', 'sometimes', 'numeric', 'exists:customers,id'],
            'department_id' => ['nullable', 'sometimes', 'numeric', 'exists:departments,id'],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'mtcdr_customer_name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user)->where('deleted_at', null)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user)->where('deleted_at', null)],
            'password' => ['nullable', 'string', 'max:255', 'confirmed'],
            'is_active' => ['nullable', 'boolean']
        ];
    }
}
