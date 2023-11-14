<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\InvoiceableType;
use Illuminate\Http\JsonResponse;

class InvoiceableTypeController extends Controller
{
    public function change_invoiceable_type(Ticket $ticket, InvoiceableType $ticketInvoiceableType): JsonResponse
    {
        $ticket->invoiceable_type()->associate($ticketInvoiceableType);
        $update = $ticket->save();

        return $update
            ? response()->json([ "msg" => "Ticket marcado como facturado correctamente."])
            : response()->json([ "msg" => "No se ha podido actualizar el estado, por favor contacte con su administrador"]);
    }

    public function get_all_invoiceable_types(): JsonResponse
    {
        return response()->json([ 'invoiceable_types' => InvoiceableType::all()->toArray() ]);
    }
}
