<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnonymousTicketRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userName' => ['required', 'string', 'max:255'],
            'userEmail' => ['required', 'email', 'max:255'],
            'userPassword' => ['required', 'string', 'max:255', 'confirmed'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'department_type_id' => ['required', 'numeric', 'exists:department_types,id'],
            'priority_id' => ['required', 'numeric', 'exists:priorities,id'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'files' => ['nullable', 'array', 'max:5']
        ];
    }
}
