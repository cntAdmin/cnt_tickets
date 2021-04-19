<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStatusRequest;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('ticketStatuses.index')->with([ 'ticket_statuses_count' => TicketStatus::count() ]);
        }
        
        $tickets_statuses = TicketStatus::filterTicketStatuses()->paginate();

        return response()->json([ 'ticket_statuses' => $tickets_statuses ], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('ticketStatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function show(TicketStatus $ticketStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketStatus $ticketStatus): View
    {
        return view('ticketStatuses.edit')->with([ 'ticket_status' => $ticketStatus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketStatus $ticketStatus)
    {
        // if(auth()->user()->can('destroy', TicketStatus::class)) {
            $deleted = $ticketStatus->delete();
        // }

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => "Estado eliminado correctamente."], 200)
            : response()->json([ "msg" => "No se ha podido eliminar este estado, por favor, contacte con el administrador."], 400);
        //
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
