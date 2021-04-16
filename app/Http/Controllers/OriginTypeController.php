<?php

namespace App\Http\Controllers;

use App\Http\Requests\OriginTypeRequest;
use App\Models\OriginType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OriginTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('origin_types.index')->with([ 'origin_types_count' => OriginType::count() ]);
        }

        $origin_types = OriginType::filterOriginTypes()->withCount('tickets')->paginate();

        return response()->json([ 'origin_types' => $origin_types ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('origin_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OriginTypeRequest $request)
    {
        $validated = $request->validated();

        $origin_type = OriginType::create([
            'name' => $validated['name']
        ]);

        return response()->json([ "msg" => "Procedencia creada correctamente."], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OriginType $originType
     * @return \Illuminate\Http\Response
     */
    public function show(OriginType $originType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OriginType $originType
     * @return \Illuminate\Http\Response
     */
    public function edit(OriginType $originType): View
    {
        return view('origin_types.edit')->with([ 'origin_type' => $originType ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OriginType $originType
     * @return \Illuminate\Http\Response
     */
    public function update(OriginTypeRequest $request, OriginType $originType)
    {
        $validated = $request->validated();

        $originType->update([
            'name' => $validated['name']
        ]);

        return response()->json([ "msg" => "Procedencia actualizada correctamente."], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OriginType $originType
     * @return \Illuminate\Http\Response
     */
    public function destroy(OriginType $originType): JsonResponse
    {
        // if(auth()->user()->can('destroy', OriginType::class)) {
            $originType->delete();
        // }

        return response()->json([ "msg" => "Procedencia eliminada correctamente."], 200);
    }

    public function get_all_origins(): JsonResponse
    {
        return response()->json([ 'origins' => OriginType::get()->toArray() ]);
    }
}
