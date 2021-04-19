<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if (!$req->ajax()) {
            $ticket_statuses = TicketStatus::withCount('tickets')->get();
            return view('tickets.index')->with([
                'ticket_statuses' => $ticket_statuses
            ]);
        }
// return $req->offset;
        $tickets = Ticket::filterTickets()->orderBy('updated_at', 'DESC');
        if($req->type == "infinite") {
            $tickets = $tickets->skip($req->offset)->take(10)->get();
        } else {
            $tickets = $tickets->paginate();
        }
        

        return response()->json([
            'tickets' => $tickets,
            'req->offset' => $req->offset,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\View
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TicketRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TicketRequest $req): JsonResponse
    {
        $validated = $req->validated();

        $created_ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'department_type_id' => $validated['department_type_id'],
            'ticket_status_id' => 1,
            'created_by' => auth()->user()->id
        ]);

        // ASSIGN DATA TO TICKET
        $created_ticket->customer()->associate($validated['customer_id']);
        $created_ticket->agent()->associate($validated['agent_id']);
        $created_ticket->ticket_type()->associate($validated['ticket_type_id']);
        $created_ticket->user()->associate($validated['user_id']);
        $created_ticket->priority()->associate($validated['priority_id']);
        $created_ticket->origin_type()->associate($validated['origin_type_id']);
        
        // SAVE ALL THE RELATIONSHIPS
        $created_ticket->save();

        // IF HAS FILES ATTACHED
        if (isset($validated['files'])) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $created_ticket->attachments()->save($attachment);
            }
        }


        return $created_ticket
            ? response()->json(['msg' => __('Ticket creado correctamente')], 200)
            : response()->json(['msg' => __('No se ha podido crear el ticket, por favor contacte con el administrador')], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ticket $ticket, Request $req)
    {
        if($req->ajax()) {
            return response()->json(['ticket' => $ticket->load(['attachments', 'comments'] )]);
        }
        return view('tickets.show')->with(['ticket' => $ticket->load(['attachments', 'comments']) ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\View
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit')->with(['ticket' => $ticket->load('attachments')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TicketRequest  $req
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $req, Ticket $ticket)
    {
        $validated = $req->validated();
        $ticket->update([
            'description' => $validated['description']
        ]);

        // ASSIGN DATA TO TICKET
        $ticket->customer()->associate($validated['customer_id']);
        $ticket->agent()->associate($validated['agent_id']);
        $ticket->ticket_type()->associate($validated['ticket_type_id']);
        $ticket->department_type()->associate($validated['department_type_id']);
        $ticket->user()->associate($validated['user_id']);
        $ticket->priority()->associate($validated['priority_id']);
        $ticket->origin_type()->associate($validated['origin_type_id']);

        // SAVE ALL THE RELATIONSHIPS
        $ticket->save();

        // IF HAS FILES ATTACHED
        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $ticket->attachments()->save($attachment);
            }
        }

        return $ticket
            ? response()->json(['msg' => __('Ticket actualizado correctamente.')], 200)
            : response()->json(['msg' => __('No se ha podido actualizar el ticket, por favor, contacte con el administrador.')], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        // if (auth()->user()->can('destroy', Ticket::class)) {
            $ticket->update(['deleted_by' => auth()->user()->id]);
            $deleted = $ticket->delete();
        // }

        return isset($deleted) && $deleted === true
            ? response()->json(['msg' => __('Ticket eliminado correctamente.')], 200)
            : response()->json(['msg' => __('No se ha podido eliminar el ticket, por favor, contacte con el administrador.')], 400);
    }

    public function count(Request $req): JsonResponse
    {
        $count = TicketStatus::where('name', $req->type)->first()->tickets->count();
        return response()->json(['count' => $count]);
    }

    public function get_ticket_attachments(Ticket $ticket): JsonResponse
    {
        return response()->json([ 'attachments' => $ticket->attachments ]);
    }
}
