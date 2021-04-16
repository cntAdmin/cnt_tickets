<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('departments.index')->with([ 'departments_count' => Department::count() ]);
        }
        $departments = Department::filterDepartments()->withCount('agents')->paginate();

        return response()->json([ 'departments' => $departments], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $department = Department::create([
            'name' => $validated['name'],
            'code' => $validated['code']
        ]);
        // ASSOCIATE USERS TO DEPARTMENT IF NEEDED
        if(isset($validated['agents'])) {
            User::find($validated['agents'])->each(function($user) use($department) {
                $user->department()->associate($department);
                $user->save();
            });
        }

        return response()->json([ "msg" => "El departamento se ha creado correctamente"]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department): View
    {
        return view('departments.edit')->with([ 'department' => $department->load('agents') ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        $validated = $request->validated();

        $department->update([
            'name' => $validated['name'],
            'code' => $validated['code']
        ]);
        // ASSOCIATIONS IF NEEDED
        if(isset($validated['agents'])) {
            $department->agents()->each(function($user) {
                $user->department()->dissociate();
                $user->save();
            });
            User::find($validated['agents'])->each(function($user) use($department) {
                $user->department()->associate($department);
                $user->save();
            });
        }

        return response()->json([ "msg" => "Departamento actualizado correctamente."], 200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department): JsonResponse
    {
        // if(auth()->user()->can('destroy', Department::class)) {
            $deleted = $department->delete();
        // }

        return isset($deleted) && $deleted 
                ? response()->json([ "El departamento se ha borrado correctamente."], 200)
                : response()->json([ "No se ha podido borrar el departamento, por favor, contacte con su administrador."], 400);

    }

    public function get_all_departments(): JsonResponse
    {
        return response()->json(['departments' => Department::get()->toArray() ]);
    }

}
