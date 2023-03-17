<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('departments.index')->with([ 'departments_count' => Department::count() ]);
        }
        $departments = Department::filterDepartments()->withCount('agents')->paginate();

        return response()->json([ 'departments' => $departments], 200);
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $department = Department::create([
            'name' => $validated['name'],
            'code' => $validated['code']
        ]);

        return response()->json([ "msg" => "El departamento se ha creado correctamente"]);
    }

    public function edit(Department $department): View
    {
        return view('departments.edit')->with([ 'department' => $department->load('agents','department_types') ]);
    }

    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        $validated = $request->validated();

        $department->update([
            'name' => $validated['name'],
            'code' => $validated['code']
        ]);

        return response()->json([ "msg" => "Departamento actualizado correctamente."], 200);
    }

    public function destroy(Department $department): JsonResponse
    {
        $deleted = $department->delete();

        return isset($deleted) && $deleted 
                ? response()->json([ "El departamento se ha borrado correctamente."], 200)
                : response()->json([ "No se ha podido borrar el departamento, por favor, contacte con su administrador."], 400);
    }

    public function get_all_departments(): JsonResponse
    {
        return response()->json(['departments' => Department::get()->toArray() ]);
    }

}
