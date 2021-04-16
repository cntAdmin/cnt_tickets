<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentTypeRequest;
use App\Models\Department;
use App\Models\DepartmentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('department_types.index')->with([ 'department_types_count' => DepartmentType::count() ]);
        }

        $department_types = DepartmentType::filterDepartmentTypes()->withCount('tickets')->paginate();

        return response()->json([ 'department_types' => $department_types ], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('department_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $department_type = DepartmentType::create([
            'name' => $validated['name']
        ]);
        // ASSOCIATIONS
        $department_type->department()->associate(Department::find($validated['department_id']));
        $department_type->save();

        return response()->json([ "msg" => "Subdepartamento creado correctamente."], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DepartmentType  $departmentType
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentType $departmentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DepartmentType  $departmentType
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentType $departmentType): View
    {
        return view('department_types.edit')->with([ 'department_type' => $departmentType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DepartmentType  $departmentType
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentTypeRequest $request, DepartmentType $departmentType): JsonResponse
    {
        $validated = $request->validated();

        $departmentType->update([
            'name' => $validated['name']
        ]);
        // SYNC ASSOCIATIONS
        $departmentType->department()->associate(Department::find($validated['department_id']));
        $departmentType->save();

        return response()->json([ "msg" => "Subdepartamento actualizado correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DepartmentType  $departmentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepartmentType $departmentType): JsonResponse
    {
        // if(auth()->user()->can('destroy', DepartmentType::class)){
            $deleted = $departmentType->delete();
        // }

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => "Subdepartamento eliminado correctamente"], 200)
            : response()->json([ "msg" => "No se ha podido eliminar el subdepartamento, por favor, contacte con el administrador"], 400);
    }

    public function get_all_department_types()
    {
        return response()->json([ 'department_types' => DepartmentType::get()->toArray() ]);
    }

    public function get_department_department_types(Department $department)
    {
        return response()->json([ 'department_types' => $department->department_types()->get()->toArray() ]);
    }
}
