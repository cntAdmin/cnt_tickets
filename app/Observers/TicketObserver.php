<?php

namespace App\Observers;

use Str;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Mail\TicketMail;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class TicketObserver
{
    protected $request;

    // Recibimos los datos del formulario
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function created(Ticket $ticket)
    {
        $ticket->update([
            'custom_id' => Str::upper($ticket->department_type->department->code . now()->year . '-' . Str::padLeft($ticket->id, 5, '0')),
        ]);

        if($ticket->ticket_type_id === 2){
            $ticket->update([
                'ticket_status_id' => 2
            ]);
        }

        // Asignamos datos al ticket
        $ticket->customer()->associate($this->request['customer_id'] ?? auth()->user()->customer_id ?: null);
        $ticket->agent()->associate($this->request['agent_id'] ?? null);
        $ticket->user()->associate($this->request['user_id'] ?? auth()->user()->id);
        $ticket->priority()->associate($this->request['priority_id'] ?? 1);
        $ticket->origin_type()->associate(auth()->user()->roles[0]->id >= 3 ? 3 : ($this->request['origin_type_id'] ?? null));
        $ticket->warranty()->associate($this->request['warranty_id'] ?? null);
        $ticket->invoiceable_type()->associate($this->request['invoiceable_type_id'] ?? null);
        $ticket->save();

        // Si tiene ficheros asignados
        if (isset($this->request['files'])) {
            foreach ($this->request->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $ticket->attachments()->save($attachment);
            }
        }

        // Si tiene horarios asignados
        if (isset($this->request['timeslots'])) {
            foreach ($this->request['timeslots'] as $key => $timeslot) {
                $ticket->ticket_timeslots()->create([
                    'start_date_time' => Carbon::parse($timeslot['start_date_time'])->toDateTimeString(),
                    'end_date_time' => null,
                    'work_time' => $timeslot['work_time']
                ]);
            }
        }

        if (!App::environment('local'))
        {
            $send_email = $this->request['send_email'];
            if($send_email == 'si'){
                if (!$ticket->customer) {
                    $sendTo = [$ticket->user->email];
                } else {
                    if ($ticket->user->email !== $ticket->customer->email) {
                        $sendTo = [$ticket->user->email, $ticket->customer->email];
                    } else {
                        $sendTo = [$ticket->user->email];
                    }
                }
                Mail::to($sendTo)
                    ->bcc('soporte@costanetworks.es')
                    ->send(new TicketMail($ticket));
            }
            else{
                $sendTo = 'soporte@costanetworks.es';
                Mail::to($sendTo)->send(new TicketMail($ticket));
            }
        }
    }
}
