<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketTimeslotRequest;
use App\Models\Ticket;
use App\Models\TicketTimeslot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketTimeslotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TicketTimeslot  $ticketTimeslot
     * @return \Illuminate\Http\Response
     */
    public function show(TicketTimeslot $ticketTimeslot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TicketTimeslot  $ticketTimeslot
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketTimeslot $ticketTimeslot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TicketTimeslot  $ticketTimeslot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketTimeslot $ticketTimeslot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketTimeslot  $ticketTimeslot
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketTimeslot $ticketTimeslot)
    {
        $deleted = $ticketTimeslot->delete();

        return $deleted
            ? response()->json([ "msg" => "Horas eliminadas correctamente."], 200)
            : response()->json([ "msg" => "No se han podido eliminar estas horas, por favor, contacte con el administrador."], 400);
        //
    }
}
