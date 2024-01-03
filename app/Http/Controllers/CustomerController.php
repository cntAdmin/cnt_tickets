<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
// use App\Jobs\CustomerFromSiptize;
use Illuminate\Http\JsonResponse;
use App\Jobs\CustomerFromDolibarr;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Models\Ticket;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('customers.index')->with([ 'customer_count' => Customer::count()]);
        }
        
        $customers = Customer::filterCustomers()->orderBy('id', 'ASC')->paginate();

        return response()->json([ 
            'customers' => $customers
        ], 200);

    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::create([
            'cif' => $validated['cif'],
            'name' => $validated['name'],
            'alias' => $validated['alias'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'town' => $validated['town'],
            'postcode' => $validated['postcode'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([ "msg" => "Cliente creado correctamente."], 200);
    }

    public function show(Customer $customer)
    {
        return abort(404, __('Página no encontrada'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit')->with([ 'customer' => $customer->load('users') ]);
    }

    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        $validated = $request->validated();

        $customer->update([
            'cif' => $validated['cif'],
            'name' => $validated['name'],
            'alias' => $validated['alias'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'town' => $validated['town'],
            'postcode' => $validated['postcode'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([ "msg" => __("Cliente actualizado correctamente.") ]);
    }

    public function get_all_customers()
    {
        return response()->json([ 'customers' => Customer::where('is_active', true)->get()->toArray() ]);
    }

    public function import_siptize_customer()
    {
        dispatch(new CustomerFromDolibarr);
    }

    public function get_customer_charts()
    {
        // Total tickets por estados
        $ticket_statuses = DB::table('tickets')
        ->join('ticket_statuses','ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->select([
            DB::raw('count(tickets.ticket_status_id) as total'), 
            DB::raw('ticket_statuses.name as estado')
        ])->groupBy('estado')
        ->where('tickets.customer_id', auth()->user()->customer_id)
        ->where('tickets.created_at', '>=', now()->subMonth())
        ->get();
        
        // Tickets de última semana por día
        $last_week_tickets = DB::table('tickets')->select([
            DB::raw('count(id) as total'), 
            DB::raw('DATE(created_at) as dia')
        ])->groupBy('dia')
        ->where('customer_id', auth()->user()->customer_id)
        ->where('created_at', '>=', now()->subWeeks(1))
        ->get();

        return response()->json([
            'ticket_statuses' => $ticket_statuses,
            'last_week_tickets' => $last_week_tickets,
        ]);
    }

    public function get_admin_charts()
    {
        // Clientes activos/inactivos
        $customers_active = Customer::select([
            DB::raw('count(id) as total'), 
        ])->groupBy('is_active')->get();

        // Top clientes con más tickets
        $top_customer_tickets = DB::table('tickets')
        ->join('customers','customers.id', '=', 'tickets.customer_id')
        ->select([
            DB::raw('count(tickets.id) as total'), 
            DB::raw('customers.name as nombre')
        ])->groupBy('nombre')
        ->orderBy('total', 'desc')->limit(6)->get();

        // Total tickets por estados
        $ticket_statuses = DB::table('tickets')
        ->join('ticket_statuses','ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->select([
            DB::raw('count(tickets.ticket_status_id) as total'), 
            DB::raw('ticket_statuses.name as estado')
        ])->groupBy('estado')->where('tickets.created_at', '>=', now()->subMonth())->get();
        
        // Tickets de última semana por día
        $last_week_tickets = DB::table('tickets')->select([
            DB::raw('count(id) as total'), 
            DB::raw('DATE(created_at) as dia')
        ])->groupBy('dia')->where('created_at', '>=', now()->subWeeks(1))->get();

        return response()->json([
            'customers_active' => $customers_active,
            'top_customer_tickets' => $top_customer_tickets,
            'ticket_statuses' => $ticket_statuses,
            'last_week_tickets' => $last_week_tickets,
        ]);
    }
}
