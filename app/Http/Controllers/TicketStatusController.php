<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\TicketStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TicketStatusRequest;

class TicketStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TicketStatus::class, 'ticketStatus');
    }

    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('ticketStatuses.index')->with([ 'ticket_statuses_count' => TicketStatus::count() ]);
        }
        
        $tickets_statuses = TicketStatus::filterTicketStatuses()->paginate();

        return response()->json([ 'ticket_statuses' => $tickets_statuses ], 200);
    }

    public function create(): View
    {
        return view('ticketStatuses.create');
    }

    public function store(TicketStatusRequest $request)
    {
        $validated = $request->validated();

        TicketStatus::create([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'icon' => $validated['icon']
        ]);

        return response()->json([ "msg" => "Estado creado correctamente."], 200);
    }

    public function edit(TicketStatus $ticketStatus): View
    {
        return view('ticketStatuses.edit')->with([ 'ticket_status' => $ticketStatus]);
    }

    public function update(TicketStatusRequest $request, TicketStatus $ticketStatus)
    {
        $validated = $request->validated();

        $ticketStatus->update([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'icon' => $validated['icon']
        ]);

        return response()->json([ "msg" => "Estado actualizado correctamente"], 200);
    }

    public function destroy(TicketStatus $ticketStatus)
    {
        $deleted = $ticketStatus->delete();

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => "Estado eliminado correctamente."], 200)
            : response()->json([ "msg" => "No se ha podido eliminar este estado, por favor, contacte con el administrador."], 400);
    }

    public function get_all_ticket_statuses(): JsonResponse
    {
        return response()->json([ 'ticket_statuses' => TicketStatus::get()->toArray() ]);
    }

    public function change_status(Ticket $ticket, TicketStatus $ticketStatus): JsonResponse
    {
        $ticket->ticket_status()->associate($ticketStatus);
        $update = $ticket->save();
        return $update
            ? response()->json([ "msg" => "Estado actualizado correctamente."])
            : response()->json([ "msg" => "No se ha podido actualizar el estado, por favor contacte con su administrador"]);
    }
}
