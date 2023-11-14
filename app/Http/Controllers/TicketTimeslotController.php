<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketTimeslot;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TicketTimeslotRequest;

class TicketTimeslotController extends Controller
{
    public function store(TicketTimeslotRequest $req): JsonResponse
    {
        $validated = $req->validated();

        $updated = Ticket::find($validated['ticket_id'])->ticket_timeslots()->create([
            'start_date_time' => $validated['start_date_time'],
            'end_date_time' => null,
            'work_time' => $validated['work_time'],
        ]);

        return $updated
            ? response()->json([ "msg" => "Fechas guardadas correctamente."], 200)
            : response()->json([ "msg" => "No se han podido guardar las fechas, por favor, contacte con el administrador."], 400);
    }

    public function destroy(TicketTimeslot $ticketTimeslot)
    {
        $deleted = $ticketTimeslot->delete();

        return $deleted
            ? response()->json([ "msg" => "Horas eliminadas correctamente."], 200)
            : response()->json([ "msg" => "No se han podido eliminar estas horas, por favor, contacte con el administrador."], 400);
    }
}
