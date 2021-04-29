<?php

namespace App\Http\Controllers;

use App\Models\InvoiceableType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceableTypeController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceableType  $invoincableType
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceableType $invoiceableType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceableType  $invoiceableType
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceableType $invoiceableType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceableType  $invoiceableType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceableType $invoiceableType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceableType  $invoiceableType
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceableType $invoiceableType)
    {
        //
    }
    public function get_all_invoiceable_types(): JsonResponse
    {
        return response()->json([ 'invoiceable_types' => InvoiceableType::all()->toArray() ]);
    }
}
