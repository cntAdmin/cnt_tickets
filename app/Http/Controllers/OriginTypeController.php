<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\OriginType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OriginTypeRequest;

class OriginTypeController extends Controller
{
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('origin_types.index')->with([ 'origin_types_count' => OriginType::count() ]);
        }

        $origin_types = OriginType::filterOriginTypes()->withCount('tickets')->paginate();

        return response()->json([ 'origin_types' => $origin_types ]);

    }

    public function create(): View
    {
        return view('origin_types.create');
    }

    public function store(OriginTypeRequest $request)
    {
        $validated = $request->validated();

        $origin_type = OriginType::create([
            'name' => $validated['name']
        ]);

        return response()->json([ "msg" => "Procedencia creada correctamente."], 200);
    }

    public function edit(OriginType $originType): View
    {
        return view('origin_types.edit')->with([ 'origin_type' => $originType ]);
    }

    public function update(OriginTypeRequest $request, OriginType $originType)
    {
        $validated = $request->validated();

        $originType->update([
            'name' => $validated['name']
        ]);

        return response()->json([ "msg" => "Procedencia actualizada correctamente."], 200);
    }

    public function destroy(OriginType $originType): JsonResponse
    {
        $originType->delete();

        return response()->json([ "msg" => "Procedencia eliminada correctamente."], 200);
    }

    public function get_all_origins(): JsonResponse
    {
        return response()->json([ 'origins' => OriginType::get()->toArray() ]);
    }
}
