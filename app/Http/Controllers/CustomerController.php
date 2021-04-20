<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            'fax' => $validated['fax'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([ "msg" => "Cliente creado correctamente."], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return abort(404, __('Página no encontrada'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit')->with([ 'customer' => $customer->load('users') ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        $validated = $request->validated();

        $customer->update([
            'name' => $validated['name'],
            'alias' => $validated['alias'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'town' => $validated['town'],
            'postcode' => $validated['postcode'],
            'phone' => $validated['phone'],
            'fax' => $validated['fax'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([ "msg" => __("Cliente actualizado correctamente.") ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function get_all_customers()
    {

        return response()->json([ 'customers' => Customer::get()->toArray() ]);
    }
}
