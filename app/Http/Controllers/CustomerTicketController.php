<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerTicketController extends Controller
{
    public function create(Customer $customer)
    {
        $this->authorize('create', [Ticket::class, $customer]);

        return view('tickets.create')->with([ 'customer' => $customer ]);
    }
}
