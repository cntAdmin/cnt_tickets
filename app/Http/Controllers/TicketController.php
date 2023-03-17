<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Priority;
use Illuminate\View\View;
use App\Models\Attachment;
use App\Models\TicketType;
use Illuminate\Support\Str;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AnnonymousTicketRequest;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    public function index(Request $req)
    {
        if (!$req->ajax()) {
            $ticket_statuses = TicketStatus::withCount('tickets')->get();
            return view('tickets.index')->with([
                'ticket_statuses' => $ticket_statuses
            ]);
        }

        $tickets = Ticket::filterTickets()->with(['comments', 'comment_attachments'])->orderBy('updated_at', 'DESC');
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

    public function create(TicketType $ticketType): View
    {
        return view('tickets.create')->with([ 'ticket_type' => $ticketType ]);
    }

    public function store(TicketRequest $req): JsonResponse
    {        
        // Si tiene firma
        $imageName = null;
        $is_signed = false;
        if ($req->signature) {
            $image_64 = $req->signature;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
            $image = str_replace($replace, '', $image_64); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(32).'.'.$extension;
            $imageName = 'images/'.$imageName;
            $is_signed = true;

            Storage::disk('public')->put($imageName, base64_decode($image));
        }

        $validated = $req->validated();
        
        $created_ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'signature' => $imageName,
            'is_signed' => $is_signed,
            'department_type_id' => $validated['department_type_id'],
            'ticket_type_id' => $validated['ticket_type_id'],
            'ticket_status_id' => 1,
            'created_by' => auth()->user()->id,
            '_token' => $this->createToken()
        ]);

        // En TicketObserver.php se encuentra el proceso de envío de email cuando se crea el ticket

        return $created_ticket
        ? response()->json(['msg' => __('Incidencia creada correctamente')], 200)
        : response()->json(['msg' => __('No se ha podido crear la incidencia, por favor contacte con el administrador')], 400);
    }

    public function show(Ticket $ticket, Request $req)
    {
        if($req->ajax()) {
            return response()->json(['ticket' => $ticket->load(['attachments', 'comments', 'ticket_type', 'ticket_timeslots'] )]);
        }

        return view('tickets.show')->with(['ticket' => $ticket->load(['attachments', 'comments', 'ticket_type', 'ticket_timeslots']) ]);
    }

    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit')->with(['ticket' => $ticket->load('attachments', 'ticket_type')]);
    }

    public function update(TicketRequest $req, Ticket $ticket)
    {
        // Si tiene firma
        $imageName = null;
        $is_signed = false;
        if ($req->signature) {
            $image_64 = $req->signature;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
            $image = str_replace($replace, '', $image_64); 
            $image = str_replace(' ', '+', $image); 
            $imageName = Str::random(32).'.'.$extension;
            $imageName = 'images/'.$imageName;
            $is_signed = true;

            Storage::disk('public')->put($imageName, base64_decode($image));
        }

        $validated = $req->validated();
        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'signature' => $imageName,
            'is_signed' => $is_signed,
            'department_type_id' => $validated['department_type_id'],
            'ticket_type_id' => $validated['ticket_type_id'],
            'ticket_status_id' => 1
        ]);

        // asignamos datos al ticket
        $ticket->customer()->associate($validated['customer_id'] ?? NULL);
        $ticket->agent()->associate($validated['agent_id'] ?? NULL);
        $ticket->ticket_type()->associate($validated['ticket_type_id'] ?? NULL);
        $ticket->invoiceable_type()->associate($validated['invoiceable_type_id'] ?? null);
        $ticket->department_type()->associate($validated['department_type_id'] ?? NULL);
        $ticket->user()->associate($validated['user_id'] ?? NULL);
        $ticket->priority()->associate($validated['priority_id'] ?? 1);
        $ticket->origin_type()->associate($validated['ticket_type_id'] === 1 ? $validated['origin_type_id'] : NULL );
        $ticket->warranty()->associate($validated['warranty_id'] ?? NULL);
        $ticket->save();

        // si tiene adjuntos
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
        // si tiene registro de horas (Sólo para parte de trabajo)
        if (isset($validated['timeslots'])) {
            $timeslots = [];
            foreach ($validated['timeslots'] as $dateTime ) {
                if($dateTime['start_date_time_picker'] && $dateTime['work_time']) {
                    $timeslots[] = [
                        'start_date_time' =>  $dateTime['start_date_time_picker'],
                        'end_date_time' => null,
                        'work_time' => $dateTime['work_time']
                    ];
                }
            }
            $ticket->ticket_timeslots()->createMany($timeslots);
        }

        return $ticket
            ? response()->json(['msg' => __('Incidencia actualizada correctamente.')], 200)
            : response()->json(['msg' => __('No se ha podido actualizar la incidencia, por favor, contacte con el administrador.')], 400);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->update(['deleted_by' => auth()->user()->id]);
        $deleted = $ticket->delete();

        return isset($deleted) && $deleted === true
            ? response()->json(['msg' => __('Incidencia eliminada correctamente.')], 200)
            : response()->json(['msg' => __('No se ha podido eliminar la incidencia, por favor, contacte con el administrador.')], 400);
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

    public function annonymous_ticket(AnnonymousTicketRequest $req): JsonResponse
    {
        $validated = $req->validated();

        $transaction = DB::transaction(function () use ($validated, $req) {
            $user = User::create([
                'name' => $validated['userName'],
                'email' => $validated['userEmail'],
                'password' => Hash::make($validated['userPassword']),
                'is_active' => true,
            ]);

            $user->syncRoles(4);
            Auth::login($user);

            $ticket = Ticket::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'department_type_id' => $validated['department_type_id'],
                'ticket_type_id' => 1,
                'ticket_status_id' => 1,
                'origin_type_id' => 3,
                'read_by_admin' => false
            ]);

            $ticket->priority()->associate(Priority::find($validated['priority_id']));
            $ticket->created_by_user()->associate($user->id);
            $ticket->user()->associate($user->id);
            $ticket->save();
            
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
        });
        
        return response()->json([ "msg" => "Usuario e incidencia creados correctamente."], 200);
    }

    public function createToken()
    {
        $token = Str::uuid();
        $exists = Ticket::where('_token', $token)->first();

        if(!$exists) {
            return $token;
        } else {
            $this->createToken();
        }
    }

    public function getTicketThroughToken(Ticket $ticket)
    {
        if($ticket->created_at->diffInMinutes(now()) > $ticket->expires_in) {
            return abort(403);
        } else {
            return view('tickets.show_annonymous')
                ->with([
                    'ticket' => $ticket
                                ->load(['comments', 'ticket_type', 'agent', 'department_type', 'priority', 'origin_type', 'warranty'])
                ]);
        }
    }
}
