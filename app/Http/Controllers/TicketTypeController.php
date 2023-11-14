<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketTypeController extends Controller
{
    public function get_all_ticket_types(): JsonResponse
    {
        return response()->json([ 'ticket_types' => TicketType::get()->toArray() ]);
    }
}
