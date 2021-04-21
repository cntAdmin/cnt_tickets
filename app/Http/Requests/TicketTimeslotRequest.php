<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketTimeslotRequest extends FormRequest
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
            'ticket_id' => ['required', 'numeric', 'exists:tickets,id'],
            'start_date_time' => ['required', 'date_format:Y-m-d\TH:i', 'before:end_date_time'],
            'end_date_time' => ['required', 'date_format:Y-m-d\TH:i', 'after:start_date_time'],
        ];
    }
}
