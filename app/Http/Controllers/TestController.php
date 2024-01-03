<?php

namespace App\Http\Controllers;

use Str;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ModelHasRoles;
use App\Models\Dolibarr\Societe;
use Illuminate\Support\Facades\DB;
use App\Models\Dolibarr\SocieteExtrafields;

class TestController extends Controller
{
    public function my_custom_tests()
    {
        // dd('hola cotilla');
        // $customersIDs = SocieteExtrafields::where('tickets', 1)->get();
        // $test = SocieteExtrafields::where('tickets', 1)->get();
        // $tickets = Ticket::all();
        // dd($tickets);

        // foreach ($tickets as $ticket){
        //     $ticket->update([
        //         'custom_ticket_id' => ($ticket->ticket_type_id === 1) ? 
        //             'TCK' . now()->year . '-' . Str::padLeft($ticket->id, 5, '0') : 
        //             'PRT' . now()->year . '-' . Str::padLeft($ticket->id, 5, '0'),
        //     ]);
        //     $ticket->save();
        // }

        $test = DB::table('tickets')
                    ->join('ticket_statuses','ticket_statuses.id', '=', 'tickets.ticket_status_id')
                    ->select([
                        DB::raw('count(tickets.ticket_status_id) as total'), 
                        DB::raw('ticket_statuses.name as estado')
                    ])->groupBy('estado')->get();

        dd($test);
    }
}