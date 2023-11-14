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
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('department_types.index')->with([ 'department_types_count' => DepartmentType::count() ]);
        }

        $department_types = DepartmentType::filterDepartmentTypes()->withCount('tickets')->paginate();

        return response()->json([ 'department_types' => $department_types ], 200);

    }

    public function create()
    {
        return view('department_types.create');
    }

    public function store(DepartmentTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $department_type = DepartmentType::create([
            'name' => $validated['name']
        ]);
        // ASSOCIATIONS
        $department_type->department()->associate(Department::find($validated['department_id']));
        $department_type->save();

        return response()->json([ "msg" => "Servicio creado correctamente."], 200);

    }

    public function edit(DepartmentType $departmentType): View
    {
        return view('department_types.edit')->with([ 'department_type' => $departmentType]);
    }

    public function update(DepartmentTypeRequest $request, DepartmentType $departmentType): JsonResponse
    {
        $validated = $request->validated();

        $departmentType->update([
            'name' => $validated['name']
        ]);
        // SYNC ASSOCIATIONS
        $departmentType->department()->associate(Department::find($validated['department_id']));
        $departmentType->save();

        return response()->json([ "msg" => "Servicio actualizado correctamente"]);
    }

    public function destroy(DepartmentType $departmentType): JsonResponse
    {
        $deleted = $departmentType->delete();

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => "Servicio eliminado correctamente"], 200)
            : response()->json([ "msg" => "No se ha podido eliminar el Servicio, por favor, contacte con el administrador"], 400);
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
