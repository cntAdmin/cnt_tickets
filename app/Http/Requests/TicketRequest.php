<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'ticket_type_id' => ['required', 'exists:ticket_types,id'],
            'invoiceable_type_id' => ['nullable', 'sometimes', 'exists:invoiceable_types,id'],
            'department_type_id' => ['required', 'exists:department_types,id'],
            'customer_id' => ['nullable', 'sometimes', 'exists:customers,id'],
            'agent_id' => ['nullable', 'sometimes', 'exists:users,id'],
            'user_id' => ['nullable', 'sometimes', 'exists:users,id'],
            'priority_id' => ['required_if:ticket_type_id,1', 'exists:priorities,id'],
            'origin_type_id' => ['nullable', 'sometimes', 'exists:origin_types,id'],
            'warranty_id' => ['required', 'exists:warranties,id'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'timeslots' => ['nullable', 'array'],
            'files' => ['nullable', 'array'],
        ];
    }
}
