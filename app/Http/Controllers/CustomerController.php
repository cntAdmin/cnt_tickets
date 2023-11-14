<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Jobs\CustomerFromSiptize;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CustomerRequest;

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
        dispatch(new CustomerFromSiptize);
    }
}
