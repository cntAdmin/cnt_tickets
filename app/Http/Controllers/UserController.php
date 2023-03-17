<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\View\View;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('users.index')->with([ 'users_count' => User::count() ]);
        }
        $users = User::filterUsers()->orderBy('id', 'ASC')->paginate();
        return response()->json([
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        $user->customer()->associate(Customer::find($validated['customer_id'] ?? null));
        $user->department()->associate(Department::find($validated['department_id'] ?? null));
        $user->syncRoles(Role::find($validated['role_id']));
        $user->save();

        return $user
            ? response()->json([ "msg" => "Usuario creado correctamente."], 200)
            : response()->json([ "msg" => "No se ha podido crear el usuario, por favor, contacte con el administrador."], 400);
    }

    public function edit(User $user): View
    {
        return view('users.edit')->with([ 'user' => $user ]);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user->update([
            'mtcdr_customer_name' => $validated['mtcdr_customer_name'],
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            'is_active' => $validated['is_active'] ? true : false,
        ]);

        $user->customer()->associate(Customer::find( $validated['customer_id'] ?? null ));
        $user->department()->associate(Department::find( $validated['department_id'] ?? null ));
        $user->syncRoles(Role::find($validated['role_id']));
        $user->save();

        return response()->json([ "msg" => "Usuario actualizado correctamente"], 200);
    }

    public function destroy(User $user)
    {
        $user->update([ 'deleted_by' => auth()->user()->id ]);
        $deleted = $user->delete();

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => __("Usuario eliminado correctamente.")], 200)
            : response()->json([ "msg" => __("El usuario no ha podido ser eliminado, por favor, contacte con su administrador")], 400);
    }

    public function get_all_agents(): JsonResponse
    {
        return response()->json(['agents' => User::role([1, 2])->get()->toArray() ], 200);
    }

    public function get_customer_users(Customer $customer): JsonResponse
    {
        return response()->json(['users' => $customer->users()->get()->toArray() ], 200);
    }

    public function profile(User $user): View
    {
        return view('profiles.index')->with([ 'user' => $user->load('customer.users') ]);
    }

    public function get_all_users_asignables(): JsonResponse
    {
        return response()->json([
            'users_asignables' => User::role([1, 2])->get()->toArray()
        ]);
    }
}
